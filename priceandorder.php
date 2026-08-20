<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

require_once __DIR__ . '/classes/PriceandorderClass.php';
require_once __DIR__ . '/classes/PriceandorderQuoteClass.php';

class Priceandorder extends Module
{
    /** Anti-spam form token: reject submissions faster than this (bots) ... */
    const TOKEN_MIN_AGE = 3;
    /** ... or older than this (stale/replayed page loads). */
    const TOKEN_MAX_AGE = 3600;
    /** Per-IP submission cap, per hour. */
    const RATE_LIMIT_PER_HOUR = 5;

    const QUOTES_PER_PAGE = 20;

    public function __construct()
    {
        $this->name = 'priceandorder';
        $this->tab = 'pricing_promotion';
        $this->version = '2.0.7';
        $this->author = 'MEG Venture';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->module_key = '982f42b2e1e92a37b588fde3f0a7706b';
        $this->ps_versions_compliancy = ['min' => '1.7.0.0', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName = $this->l('Quote Request Pro - Ask For a Custom Price');
        $this->description = $this->l('Adds a quote-request form (sidebar block and floating button) so shoppers can ask for a custom price on any product. Requests are e-mailed to you and kept in a simple list in the back office.');
        $this->confirmUninstall = $this->l('This will permanently delete your settings and every quote request you have received. Are you sure?');

        // $this->id is only set once the module row exists in ps_module (i.e.
        // already installed). PrestaShop instantiates every module class
        // just to list it in Modules > Catalog, before install() has ever
        // run and before our own tables exist, so this must not query the
        // database for a module that isn't installed yet.
        if (defined('_PS_ADMIN_DIR_') && $this->id && !$this->isConfigured()) {
            $this->warning = $this->l('Please set a recipient e-mail address in the module settings so quote requests reach you.');
        }
    }

    private function isConfigured()
    {
        $settings = PriceandorderClass::getByIdShop((int) $this->context->shop->id);

        return $settings && Validate::isLoadedObject($settings) && $settings->recmail !== '';
    }

    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if (!parent::install()
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayLeftColumn')
            || !$this->registerHook('displayRightColumn')
            || !$this->registerHook('displayFooter')
            || !$this->registerHook('dashboardZoneOne')
            || !$this->installDb()
        ) {
            return false;
        }

        foreach (Shop::getShops(false) as $shop) {
            $this->createDefaultSettings((int) $shop['id_shop']);
        }

        return true;
    }

    public function uninstall()
    {
        $queries = [
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'priceandorder`',
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'priceandorder_lang`',
            'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'priceandorder_quote`',
        ];
        foreach ($queries as $query) {
            Db::getInstance()->execute($query);
        }

        // Leftover flat Configuration keys from ancient pre-1.5 installs.
        foreach (['recmail', 'infolink', 'urgency', 'paypal', 'first_order', 'destination', 'piclink', 'captcha'] as $suffix) {
            Configuration::deleteByName($this->name . '_' . $suffix);
        }

        return parent::uninstall();
    }

    private function installDb()
    {
        $queries = [
            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'priceandorder` (
                `id_priceandorder` int(10) unsigned NOT NULL AUTO_INCREMENT,
                `id_shop` int(10) unsigned NOT NULL,
                `show_name` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_address` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_town` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_phone` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_quantity` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_destination` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_urgency` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `show_paypal` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `show_first_order` tinyint(1) unsigned NOT NULL DEFAULT 0,
                `show_floating_button` tinyint(1) unsigned NOT NULL DEFAULT 1,
                `recmail` varchar(255) NOT NULL DEFAULT \'\',
                `promo_image` varchar(255) NOT NULL DEFAULT \'\',
                PRIMARY KEY (`id_priceandorder`),
                KEY `id_shop` (`id_shop`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',

            'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'priceandorder_lang` (
                `id_priceandorder` int(10) unsigned NOT NULL,
                `id_lang` int(10) unsigned NOT NULL,
                `more_info_link` varchar(255) NOT NULL DEFAULT \'\',
                `terms_conditions_link` varchar(255) NOT NULL DEFAULT \'\',
                `privacy_policy_link` varchar(255) NOT NULL DEFAULT \'\',
                PRIMARY KEY (`id_priceandorder`, `id_lang`)
            ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8',

            $this->quoteTableSchema(),
        ];

        foreach ($queries as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }

    private function quoteTableSchema()
    {
        return 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'priceandorder_quote` (
            `id_priceandorder_quote` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `id_shop` int(10) unsigned NOT NULL,
            `id_lang` int(10) unsigned NOT NULL,
            `product` text NOT NULL,
            `customer_name` varchar(255) NOT NULL DEFAULT \'\',
            `email` varchar(255) NOT NULL,
            `phone` varchar(64) NOT NULL DEFAULT \'\',
            `address` varchar(255) NOT NULL DEFAULT \'\',
            `town` varchar(255) NOT NULL DEFAULT \'\',
            `quantity` varchar(64) NOT NULL DEFAULT \'\',
            `destination` varchar(255) NOT NULL DEFAULT \'\',
            `urgent` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `has_paypal` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `first_order` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `ip_address` varchar(45) NOT NULL DEFAULT \'\',
            `status` tinyint(1) unsigned NOT NULL DEFAULT 0,
            `date_add` datetime NOT NULL,
            `date_upd` datetime NOT NULL,
            PRIMARY KEY (`id_priceandorder_quote`),
            KEY `id_shop` (`id_shop`),
            KEY `ip_lookup` (`ip_address`, `date_add`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';
    }

    private function createDefaultSettings($idShop)
    {
        $settings = new PriceandorderClass();
        $settings->id_shop = (int) $idShop;
        $settings->show_name = true;
        $settings->show_address = true;
        $settings->show_town = true;
        $settings->show_phone = true;
        $settings->show_quantity = true;
        $settings->show_destination = true;
        $settings->show_urgency = true;
        $settings->show_paypal = false;
        $settings->show_first_order = false;
        $settings->show_floating_button = true;
        $settings->recmail = Configuration::get('PS_SHOP_EMAIL');
        $settings->promo_image = '';

        foreach (Language::getLanguages(false) as $lang) {
            $settings->more_info_link[$lang['id_lang']] = '';
            $settings->terms_conditions_link[$lang['id_lang']] = '';
            $settings->privacy_policy_link[$lang['id_lang']] = '';
        }

        return $settings->add();
    }

    /**
     * Settings are looked up per shop; if a shop somehow has no row yet
     * (new shop added after install, or a row lost some other way) a
     * default row is created on the fly instead of the form silently
     * breaking for that shop.
     */
    public function getSettingsForShop($idShop)
    {
        $settings = PriceandorderClass::getByIdShop((int) $idShop);
        if ($settings && Validate::isLoadedObject($settings)) {
            return $settings;
        }

        $this->createDefaultSettings((int) $idShop);

        return PriceandorderClass::getByIdShop((int) $idShop);
    }

    /**
     * Anti-spam token: a timestamp signed with the shop's own secret.
     * Rejects submissions that are too fast (scripted bots) or too old
     * (a stale/replayed page). No CAPTCHA, nothing for a real visitor to solve.
     */
    public function generateFormToken()
    {
        $timestamp = time();

        return $timestamp . '.' . hash_hmac('sha256', (string) $timestamp, _COOKIE_KEY_);
    }

    public static function checkFormToken($token)
    {
        if (!is_string($token) || strpos($token, '.') === false) {
            return false;
        }

        list($timestamp, $signature) = explode('.', $token, 2);
        if (!ctype_digit($timestamp)) {
            return false;
        }

        if (!hash_equals(hash_hmac('sha256', $timestamp, _COOKIE_KEY_), $signature)) {
            return false;
        }

        $age = time() - (int) $timestamp;

        return $age >= self::TOKEN_MIN_AGE && $age <= self::TOKEN_MAX_AGE;
    }

    /**
     * Terms link falls back to the shop's own Terms & Conditions CMS page
     * instead of shipping a hardcoded example URL that would point away
     * from the merchant's own site if they never touch the setting.
     */
    private function getTermsLink($settings, $idLang)
    {
        $link = trim((string) ($settings->terms_conditions_link[$idLang] ?? ''));
        if ($link !== '') {
            return $link;
        }

        $cmsId = (int) Configuration::get('PS_CONDITIONS_CMS_ID');
        if ($cmsId) {
            return $this->context->link->getCMSLink($cmsId);
        }

        return '';
    }

    public function hookDisplayHeader($params)
    {
        $this->context->controller->registerStylesheet(
            'priceandorder-front',
            'modules/' . $this->name . '/views/css/front.css'
        );
        $this->context->controller->registerJavascript(
            'priceandorder-front',
            'modules/' . $this->name . '/views/js/front.js'
        );

        Media::addJsDef([
            'priceandorderI18n' => [
                'sending' => $this->l('Sending your request...'),
                'genericError' => $this->l('Something went wrong. Please try again in a moment.'),
            ],
        ]);
    }

    private function renderQuoteForm($idShop, $context)
    {
        $settings = $this->getSettingsForShop($idShop);
        $idLang = (int) $this->context->language->id;
        $customer = $this->context->customer;
        $isLogged = $customer && $customer->isLogged();

        $this->context->smarty->assign([
            'po_context' => $context,
            'po_settings' => $settings,
            'po_is_logged' => $isLogged,
            'po_customer_name' => $isLogged ? trim($customer->firstname . ' ' . $customer->lastname) : '',
            'po_customer_email' => $isLogged ? $customer->email : '',
            'po_token' => $this->generateFormToken(),
            'po_ajax_url' => $this->context->link->getModuleLink($this->name, 'quote'),
            'po_more_info_link' => trim((string) ($settings->more_info_link[$idLang] ?? '')),
            'po_terms_link' => $this->getTermsLink($settings, $idLang),
            'po_privacy_link' => trim((string) ($settings->privacy_policy_link[$idLang] ?? '')),
            'po_promo_image_url' => $settings->promo_image !== ''
                ? $this->_path . 'views/img/uploads/' . $settings->promo_image
                : '',
            // Populated when a visitor without JavaScript lands back here
            // after a full-page form submission (see controllers/front/quote.php).
            'po_flash_success' => (bool) Tools::getValue('priceandorder_sent'),
            'po_flash_error' => (string) Tools::getValue('priceandorder_error'),
        ]);

        return $this->fetch('module:' . $this->name . '/views/templates/front/quoteform.tpl');
    }

    public function hookDisplayLeftColumn($params)
    {
        return $this->hookDisplayColumn($params);
    }

    public function hookDisplayRightColumn($params)
    {
        return $this->hookDisplayColumn($params);
    }

    private function hookDisplayColumn($params)
    {
        $idShop = (int) $this->context->shop->id;
        $this->context->smarty->assign('po_form_html', $this->renderQuoteForm($idShop, 'column'));

        return $this->fetch('module:' . $this->name . '/views/templates/front/column.tpl');
    }

    public function hookDisplayFooter($params)
    {
        $settings = $this->getSettingsForShop((int) $this->context->shop->id);
        if (!$settings->show_floating_button) {
            return '';
        }

        $this->context->smarty->assign('po_form_html', $this->renderQuoteForm((int) $this->context->shop->id, 'floating'));

        return $this->fetch('module:' . $this->name . '/views/templates/front/floating.tpl');
    }

    /**
     * Notification card on the back office Dashboard so new quote requests
     * don't go unnoticed between visits to the module's own config page.
     * Silent (no output) whenever there is nothing new to report.
     *
     * dashboardZoneOne (not displayDashboardTop) is used deliberately: the
     * latter turned out to fire on most admin pages that show a KPI row
     * (e.g. "Performance"), not just the actual Dashboard.
     */
    public function hookDashboardZoneOne($params)
    {
        $shopIds = Shop::getContextListShopID();
        if (!$shopIds) {
            return '';
        }

        $newCount = (int) Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'priceandorder_quote`
            WHERE `status` = ' . (int) PriceandorderQuoteClass::STATUS_NEW . '
            AND `id_shop` IN (' . implode(',', array_map('intval', $shopIds)) . ')'
        );

        if ($newCount < 1) {
            return '';
        }

        $this->context->smarty->assign([
            'po_new_count' => $newCount,
            'po_requests_url' => $this->context->link->getAdminLink('AdminModules', true, [], [
                'configure' => $this->name,
                'po_tab' => 'requests',
            ]),
        ]);

        return $this->fetch('module:' . $this->name . '/views/templates/admin/dashboard_notification.tpl');
    }

    /**
     * Self-healing check, run every time the config page loads. PrestaShop's
     * own "Update" button on the Modules list can fail for reasons outside
     * this module's control (a core/theme JS issue unrelated to this code —
     * "Could not perform action upgrade for module undefined" is a core
     * error, not something a module can catch or fix). Rather than depend on
     * that button working, re-assert the hooks/schema this version expects
     * and sync the stored version every time an admin actually opens this
     * page, so the module converges to the correct state regardless of
     * whether any upgrade-x.y.z.php file ever got a chance to run.
     */
    private function ensureUpToDate()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }

        if ($this->isRegisteredInHook('displayDashboardTop')) {
            $this->unregisterHook('displayDashboardTop');
        }

        foreach (['displayHeader', 'displayLeftColumn', 'displayRightColumn', 'displayFooter', 'dashboardZoneOne'] as $hook) {
            if (!$this->isRegisteredInHook($hook)) {
                $this->registerHook($hook);
            }
        }

        $this->installDb();

        if ((string) $this->database_version !== (string) $this->version) {
            $db = Db::getInstance();
            $db->execute(
                'UPDATE `' . _DB_PREFIX_ . 'module` SET `version` = \''
                . $db->escape($this->version, false, false)
                . '\' WHERE `name` = \'' . $db->escape($this->name, false, false) . '\''
            );
            $this->database_version = $this->version;
        }
    }

    public function getContent()
    {
        require_once _PS_MODULE_DIR_ . 'priceandorder/classes/MegVentureAdsWidget.php';
        $this->ensureUpToDate();

        $this->context->controller->addCSS($this->_path . 'views/css/admin.css');
        $this->context->controller->addJS($this->_path . 'views/js/admin.js');

        $output = '';

        if (Tools::isSubmit('submitPriceandorderSettings')) {
            $output .= $this->processSettingsForm();
        } elseif (Tools::isSubmit('priceandorderToggleStatus')) {
            $this->processToggleStatus();
        } elseif (Tools::isSubmit('deletepriceandorder_quote')) {
            $this->processDeleteQuote();
        }

        $this->context->smarty->assign([
            'po_module_dir' => $this->_path,
            'po_active_tab' => Tools::getValue('po_tab', 'settings'),
            'po_settings_html' => $this->renderSettingsTab($output),
            'po_requests_html' => $this->renderRequestsList(),
            'po_tutorial_html' => $this->renderTutorialTab(),
        ]);

        $content = $this->display(__FILE__, 'views/templates/admin/configure.tpl');

        return $content . MegVentureAdsWidget::render('https://megventure.com/index.php?fc=module&module=virtualproductcombination&controller=adswidget');
    }

    private function processSettingsForm()
    {
        $idShop = (int) $this->context->shop->id;
        $settings = $this->getSettingsForShop($idShop);
        $errors = [];

        $recmail = trim((string) Tools::getValue('recmail'));
        if ($recmail === '') {
            $errors[] = $this->l('The quote recipient e-mail address is required.');
        } else {
            foreach (explode(',', $recmail) as $address) {
                if (!Validate::isEmail(trim($address))) {
                    $errors[] = sprintf($this->l('"%s" is not a valid e-mail address.'), trim($address));
                }
            }
        }

        $languages = Language::getLanguages(false);
        foreach (['more_info_link', 'terms_conditions_link', 'privacy_policy_link'] as $field) {
            foreach ($languages as $language) {
                $value = trim((string) Tools::getValue($field . '_' . $language['id_lang']));
                if ($value !== '' && !preg_match('/^https?:\/\//i', $value)) {
                    $errors[] = sprintf($this->l('The link "%s" must start with http:// or https://'), $value);
                }
            }
        }

        if (!empty($_FILES['promo_image']['name'])) {
            $uploadError = ImageManager::validateUpload($_FILES['promo_image'], 4 * 1024 * 1024);
            if ($uploadError) {
                $errors[] = $uploadError;
            }
        }

        if ($errors) {
            return $this->displayError(implode('<br>', array_map([$this, 'safeOutput'], $errors)));
        }

        $settings->recmail = $recmail;
        foreach (['show_name', 'show_address', 'show_town', 'show_phone', 'show_quantity',
            'show_destination', 'show_urgency', 'show_paypal', 'show_first_order', 'show_floating_button', ] as $toggle) {
            $settings->{$toggle} = (bool) Tools::getValue($toggle);
        }

        foreach (['more_info_link', 'terms_conditions_link', 'privacy_policy_link'] as $field) {
            foreach ($languages as $language) {
                $settings->{$field}[$language['id_lang']] = trim((string) Tools::getValue($field . '_' . $language['id_lang']));
            }
        }

        if (Tools::getValue('remove_promo_image') && $settings->promo_image !== '') {
            $this->deletePromoImageFile($settings->promo_image);
            $settings->promo_image = '';
        }

        if (!empty($_FILES['promo_image']['name']) && $_FILES['promo_image']['size'] > 0) {
            $newName = $this->storePromoImageUpload($idShop);
            if ($newName) {
                $this->deletePromoImageFile($settings->promo_image);
                $settings->promo_image = $newName;
            } else {
                return $this->displayError($this->l('The image could not be saved. Please try again.'));
            }
        }

        if (!$settings->update()) {
            return $this->displayError($this->l('An error occurred while saving your settings.'));
        }

        return $this->displayConfirmation($this->l('Settings updated successfully.'));
    }

    private function safeOutput($value)
    {
        return Tools::safeOutput($value);
    }

    private function storePromoImageUpload($idShop)
    {
        $uploadDir = _PS_MODULE_DIR_ . $this->name . '/views/img/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $size = @getimagesize($_FILES['promo_image']['tmp_name']);
        if (!$size) {
            return false;
        }

        $maxWidth = 400;
        $maxHeight = 280;
        $ratio = min($maxWidth / $size[0], $maxHeight / $size[1], 1);
        $width = (int) round($size[0] * $ratio);
        $height = (int) round($size[1] * $ratio);

        $extension = strtolower(substr($_FILES['promo_image']['name'], strrpos($_FILES['promo_image']['name'], '.') + 1));
        $newName = 'promo_' . (int) $idShop . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $destination = $uploadDir . $newName;

        // Resize + re-encode (not a plain file move) so any bytes appended
        // after valid image data cannot be smuggled onto the server, and so
        // the image keeps its aspect ratio instead of being stretched.
        if (!ImageManager::resize($_FILES['promo_image']['tmp_name'], $destination, $width, $height)) {
            return false;
        }

        return $newName;
    }

    private function deletePromoImageFile($filename)
    {
        if ($filename === '') {
            return;
        }
        $path = _PS_MODULE_DIR_ . $this->name . '/views/img/uploads/' . basename($filename);
        if (is_file($path)) {
            @unlink($path);
        }
    }

    private function processToggleStatus()
    {
        $id = (int) Tools::getValue('id_priceandorder_quote');
        $quote = new PriceandorderQuoteClass($id);
        if (Validate::isLoadedObject($quote) && (int) $quote->id_shop === (int) $this->context->shop->id) {
            $quote->status = $quote->status == PriceandorderQuoteClass::STATUS_HANDLED
                ? PriceandorderQuoteClass::STATUS_NEW
                : PriceandorderQuoteClass::STATUS_HANDLED;
            $quote->update();
        }
    }

    private function processDeleteQuote()
    {
        $id = (int) Tools::getValue('id_priceandorder_quote');
        $quote = new PriceandorderQuoteClass($id);
        if (Validate::isLoadedObject($quote) && (int) $quote->id_shop === (int) $this->context->shop->id) {
            $quote->delete();
        }
    }

    private function renderSettingsTab($formOutput)
    {
        $idShop = (int) $this->context->shop->id;
        $settings = $this->getSettingsForShop($idShop);
        $languages = Language::getLanguages(false);

        $this->context->smarty->assign([
            'po_form_output' => $formOutput,
            'po_settings' => $settings,
            'po_languages' => $languages,
            'po_default_lang' => (int) Configuration::get('PS_LANG_DEFAULT'),
            'po_promo_image_url' => $settings->promo_image !== ''
                ? $this->_path . 'views/img/uploads/' . $settings->promo_image
                : '',
            'po_current_index' => AdminController::$currentIndex . '&configure=' . $this->name,
            'po_token' => Tools::getAdminTokenLite('AdminModules'),
        ]);

        return $this->fetch('module:' . $this->name . '/views/templates/admin/_settings.tpl');
    }

    private function renderRequestsList()
    {
        $idShop = (int) $this->context->shop->id;
        $statusFilter = Tools::getValue('po_status', '');
        $page = max(1, (int) Tools::getValue('po_page', 1));
        $offset = (($page - 1) * self::QUOTES_PER_PAGE);

        $where = 'WHERE `id_shop` = ' . $idShop;
        if ($statusFilter === 'new') {
            $where .= ' AND `status` = ' . (int) PriceandorderQuoteClass::STATUS_NEW;
        } elseif ($statusFilter === 'handled') {
            $where .= ' AND `status` = ' . (int) PriceandorderQuoteClass::STATUS_HANDLED;
        }

        $total = (int) Db::getInstance()->getValue(
            'SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'priceandorder_quote` ' . $where
        );

        $rows = Db::getInstance()->executeS(
            'SELECT * FROM `' . _DB_PREFIX_ . 'priceandorder_quote` ' . $where . '
            ORDER BY `date_add` DESC
            LIMIT ' . (int) $offset . ', ' . (int) self::QUOTES_PER_PAGE
        );

        $this->context->smarty->assign([
            'po_quotes' => $rows,
            'po_total' => $total,
            'po_page' => $page,
            'po_pages' => max(1, (int) ceil($total / self::QUOTES_PER_PAGE)),
            'po_status_filter' => $statusFilter,
            'po_current_index' => AdminController::$currentIndex . '&configure=' . $this->name,
            'po_token' => Tools::getAdminTokenLite('AdminModules'),
        ]);

        return $this->fetch('module:' . $this->name . '/views/templates/admin/_requests.tpl');
    }

    private function renderTutorialTab()
    {
        return $this->fetch('module:' . $this->name . '/views/templates/admin/_tutorial.tpl');
    }
}
