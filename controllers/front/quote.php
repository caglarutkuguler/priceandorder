<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture
 * @license   All rights reserved
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Handles quote-request submissions from both the sidebar block and the
 * floating button (they share the same form partial and POST here).
 * Reached at index.php?fc=module&module=priceandorder&controller=quote
 */
class PriceandorderQuoteModuleFrontController extends ModuleFrontController
{
    public $auth = false;

    public function initContent()
    {
        // Guard against any stray PHP warning/notice output (e.g. from a
        // misconfigured mail setup) corrupting the JSON response body, which
        // would otherwise surface to the visitor as a generic JS error with
        // no clue as to the real cause. Everything printed here is discarded;
        // real problems are logged instead via PrestaShopLogger.
        ob_start();
        try {
            $response = $this->handleSubmission();
        } catch (Throwable $e) {
            PrestaShopLogger::addLog('Priceandorder: quote submission crashed: ' . $e->getMessage(), 3);
            $response = ['success' => false, 'message' => $this->trans('We could not save your request. Please try again.')];
        }
        $stray = ob_get_clean();
        if ($stray !== '') {
            PrestaShopLogger::addLog('Priceandorder: unexpected output during quote submission: ' . substr($stray, 0, 500), 2);
        }

        if (Tools::getValue('ajax')) {
            header('Content-Type: application/json; charset=utf-8');
            die(json_encode($response));
        }

        $this->redirectBack($response);
    }

    private function redirectBack($response)
    {
        $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
        $shopHost = parse_url($this->context->shop->getBaseURL(true), PHP_URL_HOST);
        $refererHost = $referer !== '' ? parse_url($referer, PHP_URL_HOST) : '';
        $target = ($refererHost && $refererHost === $shopHost)
            ? $referer
            : $this->context->link->getPageLink('index');

        $target = preg_replace('/[?&]priceandorder_(sent|error)=[^&#]*/', '', $target);
        $separator = (strpos($target, '?') === false) ? '?' : '&';
        $flag = $response['success']
            ? 'priceandorder_sent=1'
            : 'priceandorder_error=' . urlencode($response['message']);

        Tools::redirect($target . $separator . $flag . '#priceandorder-form');
    }

    private function handleSubmission()
    {
        if (!Tools::isSubmit('priceandorder_submit')) {
            return ['success' => false, 'message' => $this->trans('Invalid request.')];
        }

        // Honeypot: real visitors never see or fill this field in.
        if (trim((string) Tools::getValue('priceandorder_confirm_email')) !== '') {
            return ['success' => true, 'message' => $this->getThankYouMessage()];
        }

        if (!Priceandorder::checkFormToken(Tools::getValue('priceandorder_token'))) {
            return ['success' => false, 'message' => $this->trans('Your session has expired. Please reload the page and try again.')];
        }

        $ip = (string) Tools::getRemoteAddr();
        if ($ip !== '') {
            $since = date('Y-m-d H:i:s', strtotime('-1 hour'));
            if (PriceandorderQuoteClass::countRecentByIp($ip, $since) >= Priceandorder::RATE_LIMIT_PER_HOUR) {
                return ['success' => false, 'message' => $this->trans('Too many requests from your connection. Please try again later.')];
            }
        }

        $idShop = (int) $this->context->shop->id;
        /** @var Priceandorder $module */
        $module = $this->module;
        $settings = $module->getSettingsForShop($idShop);
        if (!$settings) {
            return ['success' => false, 'message' => $this->trans('We could not save your request. Please try again.')];
        }

        $product = strip_tags(trim((string) Tools::getValue('product')));
        if ($product === '') {
            return ['success' => false, 'message' => $this->trans('Please describe the product you are looking for.')];
        }
        if (Tools::strlen($product) > 2000) {
            $product = Tools::substr($product, 0, 2000);
        }

        $customer = $this->context->customer;
        $isLogged = $customer && $customer->isLogged();

        if ($isLogged) {
            $email = $customer->email;
            $customerName = trim($customer->firstname . ' ' . $customer->lastname);
        } else {
            $email = trim((string) Tools::getValue('email'));
            $customerName = $settings->show_name ? strip_tags(trim((string) Tools::getValue('customer_name'))) : '';
        }

        if (!Validate::isEmail($email)) {
            return ['success' => false, 'message' => $this->trans('Please enter a valid e-mail address.')];
        }

        if (!$isLogged && $settings->show_name && $customerName === '') {
            return ['success' => false, 'message' => $this->trans('Please enter your name.')];
        }

        if (!Tools::getValue('priceandorder_consent')) {
            return ['success' => false, 'message' => $this->trans('Please accept the Terms & Privacy Policy to continue.')];
        }

        $quote = new PriceandorderQuoteClass();
        $quote->id_shop = $idShop;
        $quote->id_lang = (int) $this->context->language->id;
        $quote->product = $product;
        $quote->customer_name = $customerName;
        $quote->email = $email;
        $quote->phone = $settings->show_phone ? strip_tags(trim((string) Tools::getValue('phone'))) : '';
        $quote->address = $settings->show_address ? strip_tags(trim((string) Tools::getValue('address'))) : '';
        $quote->town = $settings->show_town ? strip_tags(trim((string) Tools::getValue('town'))) : '';
        $quote->quantity = $settings->show_quantity ? strip_tags(trim((string) Tools::getValue('quantity'))) : '';
        $quote->destination = $settings->show_destination ? strip_tags(trim((string) Tools::getValue('destination'))) : '';
        $quote->urgent = $settings->show_urgency ? (bool) Tools::getValue('urgent') : false;
        $quote->has_paypal = $settings->show_paypal ? (bool) Tools::getValue('has_paypal') : false;
        $quote->first_order = $settings->show_first_order ? (bool) Tools::getValue('first_order') : false;
        $quote->ip_address = $ip;
        $quote->status = PriceandorderQuoteClass::STATUS_NEW;

        if (!$quote->add()) {
            return ['success' => false, 'message' => $this->trans('We could not save your request. Please try again.')];
        }

        // The request is already safely saved at this point (visible in the
        // Quote Requests tab regardless). A broken mail server on the host
        // must not turn a successful save into a customer-facing error.
        try {
            $this->sendNotificationMails($settings, $quote);
        } catch (Throwable $e) {
            PrestaShopLogger::addLog(
                'Priceandorder: sending notification e-mails crashed for quote #' . (int) $quote->id . ': ' . $e->getMessage(),
                3,
                null,
                'PriceandorderQuote',
                (int) $quote->id
            );
        }

        return ['success' => true, 'message' => $this->getThankYouMessage()];
    }

    private function getThankYouMessage()
    {
        return $this->trans('Thank you! Your quote request has been sent. We will get back to you shortly.');
    }

    private function trans($string)
    {
        return $this->module->l($string, 'quote');
    }

    private function sendNotificationMails($settings, PriceandorderQuoteClass $quote)
    {
        $idShop = (int) $this->context->shop->id;
        $idLang = (int) $quote->id_lang;
        $shopName = Configuration::get('PS_SHOP_NAME', null, null, $idShop);
        $shopEmail = Configuration::get('PS_SHOP_EMAIL', null, null, $idShop);
        $logoFile = Configuration::get('PS_LOGO');
        $shopUrl = rtrim($this->context->shop->getBaseURL(true), '/');
        $shopLogo = $logoFile ? ($shopUrl . '/img/' . $logoFile) : '';

        $yes = $this->trans('Yes');
        $no = $this->trans('No');

        $vars = [
            '{shop_name}' => $shopName,
            '{shop_url}' => $shopUrl,
            '{shop_logo}' => $shopLogo,
            '{customer_name}' => $quote->customer_name !== '' ? $quote->customer_name : $this->trans('Guest'),
            '{email}' => $quote->email,
            '{phone}' => $quote->phone,
            '{address}' => $quote->address,
            '{town}' => $quote->town,
            '{product}' => $quote->product,
            '{quantity}' => $quote->quantity,
            '{destination}' => $quote->destination,
            '{urgent}' => $quote->urgent ? $yes : $no,
            '{has_paypal}' => $quote->has_paypal ? $yes : $no,
            '{first_order}' => $quote->first_order ? $yes : $no,
        ];

        $mailDir = _PS_MODULE_DIR_ . $this->module->name . '/mails/';

        $customerSent = @Mail::Send(
            $idLang,
            'quote_customer',
            $this->trans('Your quote request'),
            $vars,
            $quote->email,
            null,
            $shopEmail,
            $shopName,
            null,
            null,
            $mailDir,
            false,
            $idShop
        );

        $recipients = array_values(array_filter(array_map('trim', explode(',', $settings->recmail))));
        $adminSent = false;
        if ($recipients) {
            $adminSent = @Mail::Send(
                $idLang,
                'quote_admin',
                $this->trans('New quote request received'),
                $vars,
                $recipients,
                null,
                $quote->email,
                $quote->customer_name !== '' ? $quote->customer_name : $shopName,
                null,
                null,
                $mailDir,
                false,
                $idShop
            );
        }

        if (!$customerSent || !$adminSent) {
            PrestaShopLogger::addLog(
                'Priceandorder: a notification e-mail failed to send for quote request #' . (int) $quote->id,
                2,
                null,
                'PriceandorderQuote',
                (int) $quote->id
            );
        }
    }
}
