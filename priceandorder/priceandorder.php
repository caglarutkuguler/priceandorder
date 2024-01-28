<?php
/**
 *    Module Name: Price & Order Column Quote Form for Prestashop
 *
 *    Module URI: Please contact with info@megventure.com
 *    Description: A Price Quoting Module for Demanded Orders
 *    Version: 1.9.5
 *
 * @author    Caglar Guler <info@megventure.com>
 * @copyright 2007-2023 MEG Venture
 * @license   Copyright 2012, Caglar Guler (info@megventure.com)
 *    This program is not a free software: you can't redistribute it and/or modify
 *    it. All rights reserved.
 *
 *    This copyright notice  and licence should be retained in all modules based on this framework.
 *    This does not affect your rights to assert copyright over your own original work.
 *    However, the license of the audio player used in this module is provided below.
 *    This license is also in force.
 *
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
class Priceandorder extends Module
{
    private $_html = '';
    private $_postErrors = [];
    public $recmail;
    public $infolink;
    public $destination;
    public $piclink;
    public $captcha;
    public $urgency;
    public $paypal;
    public $first_order;
    public function __construct()
    {
        $this->name = 'priceandorder';
        $this->tab = 'pricing_promotion';
        $this->version = '1.9.5';
        $this->bootstrap = true;
        $this->author = 'MEG Venture';
        $this->need_instance = 0;
        $this->module_key = "982f42b2e1e92a37b588fde3f0a7706b";
        $config = Configuration::getMultiple(array($this->name . '_recmail', $this->name . '_infolink', $this->name . '_urgency', $this->name . '_paypal', $this->name . '_first_order', $this->name . '_destination', $this->name . '_piclink', $this->name . '_captcha'));
        if (isset($config[$this->name . '_recmail'])) {
            $this->recmail = $config[$this->name . '_recmail'];
        }
        if (isset($config[$this->name . '_infolink'])) {
            $this->infolink = $config[$this->name . '_infolink'];
        }
        if (isset($config[$this->name . '_urgency'])) {
            $this->urgency = $config[$this->name . '_urgency'];
        }
        if (isset($config[$this->name . '_paypal'])) {
            $this->paypal = $config[$this->name . '_paypal'];
        }
        if (isset($config[$this->name . '_first_order'])) {
            $this->first_order = $config[$this->name . '_first_order'];
        }
        if (isset($config[$this->name . '_destination'])) {
            $this->destination = $config[$this->name . '_destination'];
        }
        if (isset($config[$this->name . '_piclink'])) {
            $this->piclink = $config[$this->name . '_piclink'];
        }
        if (isset($config[$this->name . '_captcha'])) {
            $this->captcha = $config[$this->name . '_captcha'];
        }
        parent::__construct();
        $this->displayName = $this->l('Price and Order - Column Quote Form');
        $this->description = $this->l('Use this module to put a column form to your website to be able to provide the best and fastest price quotes to the demanded products.');
        /* Backward compatibility */
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            require _PS_MODULE_DIR_ . $this->name . '/backward_compatibility/backward.php';
        }
        $path = dirname(__FILE__);
        if (strpos(__FILE__, 'Module.php') !== false) {
            $path .= '/../modules/' . $this->name;
        }
        include_once $path . '/classes/PriceandorderClass.php';
        $this->confirmUninstall = $this->l('Are you sure you want to delete your details?');
        if (!isset($this->recmail) || !isset($this->infolink) || !isset($this->destination) || !isset($this->piclink) || !isset($this->captcha)) {
            $this->warning = $this->l('Required fields and options must be configured in order to use this module correctly.');
        }
        if (!isset($this->recmail) or ($this->recmail == "demo@demo.com")) {
            $this->warning = $this->l('Quote recipient e-mail can not be empty/should be changed in order to use this module correctly.');
        }
    }
    public function install()
    {
        if (Shop::isFeatureActive()) {
            Shop::setContext(Shop::CONTEXT_ALL);
        }
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            if (!parent::install()
                || !$this->registerHook('rightColumn')
                || !$this->registerHook('header')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_recmail", "' . Configuration::get('PS_SHOP_EMAIL') . '", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_destination", "display:block", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_captcha", "display:none", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_urgency", "display:block", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_paypal", "display:block", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_infolink", "http://' . Configuration::get('PS_SHOP_DOMAIN') . '", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_piclink", "http://' . Configuration::get('PS_SHOP_DOMAIN') . '/modules/priceandorder/views/img/ms.png", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            if (!Db::getInstance()->Execute('
		INSERT INTO `' . _DB_PREFIX_ . 'configuration`(`id_configuration`, `name`, `value`, `date_add`, `date_upd`)
		VALUES("","priceandorder_first_order", "display:block", "2012-03-29 00:08:22", "2012-04-02 12:42:16")')) {
                return false;
            }
            return true;
        } else {
            if (!parent::install()
                || !$this->registerHook('displayHeader')
                || !$this->registerHook('displayLeftColumn')) {
                return false;
            }
            $res = Db::getInstance()->execute('
			CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'priceandorder` (
			`id_priceandorder` int(10) unsigned NOT NULL auto_increment,
			`id_shop` int(10) unsigned NOT NULL ,
			`contactaddress` text NOT NULL,
			`contacttown` varchar(255) NOT NULL,
			`contactemail` varchar(255) NOT NULL,
			`destination` varchar(255) NOT NULL,
			`quantity` varchar(255) NOT NULL,
			`customername` varchar(255) NOT NULL,
			`phone` varchar(255) NOT NULL,
			`captcha` varchar(255) NOT NULL,
			`urgency` varchar(255) NOT NULL,
			`paypal` varchar(255) NOT NULL,
			`first_order` varchar(255) NOT NULL,
			PRIMARY KEY (`id_priceandorder`))
			ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
            if ($res) {
                $res &= Db::getInstance()->execute('
				CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'priceandorder_lang` (
				`id_priceandorder` int(10) unsigned NOT NULL,
				`id_lang` int(10) unsigned NOT NULL,
                `more_info_link` varchar(255) NOT NULL,
                `terms_conditions_link` varchar(255) NOT null,
                `privacy_policy_link` varchar(255) NOT null,
				`most_ordered` varchar(255) NOT NULL,
				`recmail` varchar(255) NOT NULL,
				PRIMARY KEY (`id_priceandorder`, `id_lang`))
				ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8');
            }
            if ($res) {
                foreach (Shop::getShops(false) as $shop) {
                    $res &= $this->createExamplePriceandorder($shop['id_shop']);
                }
            }
            if (!$res) {
                $res &= $this->uninstall();
            }
            return $res;
        }
    }
    private function createExamplePriceandorder($id_shop)
    {
        $priceandorder = new PriceandorderClass();
        $priceandorder->id_shop = (int) $id_shop;
        $priceandorder->contactaddress = 'Display:block';
        $priceandorder->contacttown = 'Display:block';
        $priceandorder->contactemail = 'Display:block';
        $priceandorder->destination = 'Display:block';
        $priceandorder->quantity = 'Display:block';
        $priceandorder->customername = 'Display:block';
        $priceandorder->phone = 'Display:block';
        $priceandorder->captcha = 'Display:none';
        $priceandorder->urgency = 'Display:block';
        $priceandorder->paypal = 'Display:block';
        $priceandorder->first_order = 'Display:block';
        foreach (Language::getLanguages(false) as $lang) {
            $priceandorder->more_info_link[$lang['id_lang']] = 'https://www.prestashop.com';
            $priceandorder->terms_conditions_link[$lang['id_lang']] = 'https://www.prestashop.com/en/terms-of-use';
            $priceandorder->privacy_policy_link[$lang['id_lang']] = 'https://www.prestashop.com/en/prestashop-account-privacy';
            $priceandorder->most_ordered[$lang['id_lang']] = 'views/img/ms.png';
            $priceandorder->recmail[$lang['id_lang']] = 'demo@demo.com';
        }
        return $priceandorder->add();
    }
    public function uninstall()
    {
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            if (!Configuration::deleteByName($this->name . '_recmail')
                || !Configuration::deleteByName($this->name . '_infolink')
                || !Configuration::deleteByName($this->name . '_destination')
                || !Configuration::deleteByName($this->name . '_urgency')
                || !Configuration::deleteByName($this->name . '_paypal')
                || !Configuration::deleteByName($this->name . '_piclink')
                || !Configuration::deleteByName($this->name . '_first_order')
                || !Configuration::deleteByName($this->name . '_captcha')
                || !parent::uninstall()) {
                return false;
            }
            return true;
        } else {
            $res = Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'priceandorder`');
            $res &= Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'priceandorder_lang`');
            if (!$res || !parent::uninstall()) {
                return false;
            }
            return true;
        }
    }
    private function _postValidation()
    {
        if (Tools::isSubmit('submit')) {
            if (!Tools::getValue('captcha_settings')) {
                $this->_postErrors[] = $this->l('Changes are not saved. CAPTCHA Settings are required.');
            } elseif (!Tools::getValue('recipient_mail')) {
                $this->_postErrors[] = $this->l('Changes are not saved. Quote recipient e-mail address can\'t be empty.');
            } elseif (!Tools::getValue('destination_settings')) {
                $this->_postErrors[] = $this->l('Changes are not saved. Destination Settings are required.');
            } elseif (!Tools::getValue('urgency_settings')) {
                $this->_postErrors[] = $this->l('Changes are not saved. Urgency Settings are required.');
            } elseif (!Tools::getValue('paypal_settings')) {
                $this->_postErrors[] = $this->l('Changes are not saved. Paypal Settings are required.');
            } elseif (!Tools::getValue('first_order_settings')) {
                $this->_postErrors[] = $this->l('Changes are not saved. First Order Settings are required.');
            }
        }
    }
    private function initForm()
    {
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
        } else {
            $languages = Language::getLanguages(false);
            foreach ($languages as $k => $language) {
                $languages[$k]['is_default'] = (int) ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));
            }
            $helper = new HelperForm();
            $helper->module = $this;
            $helper->name_controller = 'priceandorder';
            $helper->identifier = $this->identifier;
            $helper->token = Tools::getAdminTokenLite('AdminModules');
            $helper->languages = $languages;
            $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;
            $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
            $helper->allow_employee_form_lang = true;
            $helper->toolbar_scroll = true;
            $helper->toolbar_btn = $this->initToolbar();
            $helper->title = $this->displayName;
            $helper->submit_action = 'submitUpdatePriceandorder';
            $this->fields_form[0]['form'] = array(
                'tinymce' => true,
                'legend' => array(
                    'title' => $this->displayName,
                    'image' => $this->_path . 'logo.gif',
                ),
                'submit' => array(
                    'name' => 'submitUpdatePriceandorder',
                    'title' => $this->l('   Save   '),
                    'class' => 'button',
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Contact name'),
                        'name' => 'customername',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for the contact full name of the customer if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Contact address'),
                        'name' => 'contactaddress',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for the contact address of the customer if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Contact Town'),
                        'name' => 'contacttown',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for the contact town of the customer if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Contact Phone'),
                        'name' => 'phone',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for the contact phone number of the customer if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Contact Email'),
                        'name' => 'contactemail',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for the contact email of the customer if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Quantity option'),
                        'name' => 'quantity',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for quantity requested if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Shipping Destination Option'),
                        'name' => 'destination',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for shipping destination if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Order Urgency Option'),
                        'name' => 'urgency',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks for the urgency status of the customer order if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Paypal Account Question Option'),
                        'name' => 'paypal',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks customer if she/he has a Paypal account if it is enabled.'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('First Order Question Option'),
                        'name' => 'first_order',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('Asks customer whether it is his/her first order if it is enabled.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Quote Recipient E-mail'),
                        'name' => 'recmail',
                        'lang' => true,
                        'desc' => $this->l('This is the e-mail address that will be notified about the quote requests. For multiple mail addresses comma-delimited'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('More Info Link'),
                        'name' => 'more_info_link',
                        'lang' => true,
                        'desc' => $this->l('This the link on the side form more info link. Should start with http://'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Terms and Conditions Page Link'),
                        'name' => 'terms_conditions_link',
                        'lang' => true,
                        'hint' => $this->l('This is the link of your website\'s Terms and Conditions Page to comply with the GDPR rules.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Privacy Policy Page Link'),
                        'name' => 'privacy_policy_link',
                        'lang' => true,
                        'hint' => $this->l('This is the link of your website\'s Privacy Policy Page to comply with the GDPR rules.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Most Ordered Product Picture Link'),
                        'name' => 'most_ordered',
                        'lang' => true,
                        'desc' => $this->l('Picture size should be 144x98 px. Ex: /modules/priceandorder/views/img/ms.png'),
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->l('Side Form CAPTCHA Security'),
                        'name' => 'captcha',
                        'required' => false,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'on',
                                'value' => 'Display:block',
                                'label' => $this->l('On'),
                            ),
                            array(
                                'id' => 'off',
                                'value' => 'Display:none',
                                'label' => $this->l('Off'),
                            ),
                        ),
                        'desc' => $this->l('CAPTCHA prevents robot spam mails, however bothers visitors. It is automatically disabled for logged in customers.'),
                    ),
                ),
            );
            return $helper;
        }
    }
    private function initToolbar()
    {
        $this->toolbar_btn['save'] = array(
            'href' => '#',
            'desc' => $this->l('Save'),
        );
        return $this->toolbar_btn;
    }
    public function getContent()
    {
        $this->_html = '';
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            global $cookie;
            if (Tools::isSubmit('submit')) {
                // Forbidden key
                $forbidden = array('submit');
                $this->_postValidation();
                if (!sizeof($this->_postErrors)) {
                    $this->postProcess();
                } else {
                    foreach ($this->_postErrors as $err) {
                        $this->_html .= $this->displayError($err);
                    }
                }
            }
            return $this->_html . $this->displayForm();
        } else {
            $this->postProcess();
            $helper = $this->initForm();
            $id_shop = (int) $this->context->shop->id;
            $priceandorder = PriceandorderClass::getByIdShop($id_shop);
            if (!$priceandorder) {
                $this->createExamplePriceandorder($id_shop);
            }
            foreach ($this->fields_form[0]['form']['input'] as $input) {
                $helper->fields_value[$input['name']] = $priceandorder->{$input['name']};
            }
            $this->_html .= $helper->generateForm($this->fields_form);
            return $this->_html;
        }
    }
    public function postProcess()
    {
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            if (Shop::isFeatureActive()) {
                $idShop = Shop::getContextShopID(true);
                $idShopGroup = Shop::getContextShopGroupID(true);
                Configuration::updateValue($this->name . '_recmail', Tools::getValue('recipient_mail'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_destination', Tools::getValue('destination_settings'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_urgency', Tools::getValue('urgency_settings'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_paypal', Tools::getValue('paypal_settings'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_first_order', Tools::getValue('first_order_settings'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_infolink', Tools::getValue('link'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_piclink', Tools::getValue('picture_link'), false, $idShopGroup, $idShop);
                Configuration::updateValue($this->name . '_captcha', Tools::getValue('captcha_settings'), false, $idShopGroup, $idShop);
            } else {
                Configuration::updateValue($this->name . '_recmail', Tools::getValue('recipient_mail'));
                Configuration::updateValue($this->name . '_destination', Tools::getValue('destination_settings'));
                Configuration::updateValue($this->name . '_urgency', Tools::getValue('urgency_settings'));
                Configuration::updateValue($this->name . '_paypal', Tools::getValue('paypal_settings'));
                Configuration::updateValue($this->name . '_first_order', Tools::getValue('first_order_settings'));
                Configuration::updateValue($this->name . '_infolink', Tools::getValue('link'));
                Configuration::updateValue($this->name . '_piclink', Tools::getValue('picture_link'));
                Configuration::updateValue($this->name . '_captcha', Tools::getValue('captcha_settings'));
            }
            $this->_html .= $this->displayConfirmation($this->l('Settings updated successfully'));
        } else {
            $errors = '';
            $id_shop = (int) $this->context->shop->id;
            if (Tools::isSubmit('submitUpdatePriceandorder')) {
                $id_shop = (int) $this->context->shop->id;
                $priceandorder = PriceandorderClass::getByIdShop($id_shop);
                $priceandorder->copyFromPost();
                $priceandorder->update();
                $this->_html .= $errors == '' ? $this->displayConfirmation($this->l('Settings updated successfully')) : $errors;
            }
        }
    }
    private function _displayForm()
    {
        if (Shop::isFeatureActive()) {
            $idShop = Shop::getContextShopID(true);
            $idShopGroup = Shop::getContextShopGroupID(true);
            $this->smarty->assign(array(
                'request_uri' => $_SERVER['REQUEST_URI'],
                'path' => $this->_path,
                'destination' => Configuration::get('priceandorder_destination', null, $idShopGroup, $idShop),
                'urgency' => Configuration::get('priceandorder_urgency', null, $idShopGroup, $idShop),
                'paypal' => Configuration::get('priceandorder_paypal', null, $idShopGroup, $idShop),
                'first_order' => Configuration::get('priceandorder_first_order', null, $idShopGroup, $idShop),
                'recmail' => Configuration::get('priceandorder_recmail', null, $idShopGroup, $idShop),
                'infolink' => Configuration::get('priceandorder_infolink', null, $idShopGroup, $idShop),
                'piclink' => Configuration::get('priceandorder_piclink', null, $idShopGroup, $idShop),
                'captcha' => Configuration::get('priceandorder_captcha', null, $idShopGroup, $idShop),
            ));
        } else {
            $this->smarty->assign(array(
                'request_uri' => $_SERVER['REQUEST_URI'],
                'path' => $this->_path,
                'destination' => Configuration::get('priceandorder_destination'),
                'urgency' => Configuration::get('priceandorder_urgency'),
                'paypal' => Configuration::get('priceandorder_paypal'),
                'first_order' => Configuration::get('priceandorder_first_order'),
                'recmail' => Configuration::get('priceandorder_recmail'),
                'infolink' => Configuration::get('priceandorder_infolink'),
                'piclink' => Configuration::get('priceandorder_piclink'),
                'captcha' => Configuration::get('priceandorder_captcha'),
            ));
        }
        return $this->display(__FILE__, 'views/templates/admin/form.tpl');
    }
    public function hookDisplayHeader($params)
    {
        return $this->hookHeader($params);
    }
    public function hookHeader($params)
    {
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            return $this->display(__FILE__, 'views/templates/front/priceandorder_header14.tpl');
        } else {
            if (version_compare(_PS_VERSION_, '1.6.0.0 ', '>')) {
                $this->context->controller->addCSS(($this->_path) . 'views/css/priceandorder.css', 'all');
            } else {
                $this->context->controller->addCSS(($this->_path) . 'views/css/priceandorder17.css', 'all');
            }
            $id_shop = (int) $this->context->shop->id;
            $priceandorder = PriceandorderClass::getByIdShop($id_shop);
            $priceandorder = new PriceandorderClass((int) $priceandorder->id, $this->context->language->id);
            $this->smarty->assign(array(
                'priceandorder' => $priceandorder,
                'default_lang' => (int) $this->context->language->id,
                'id_lang' => $this->context->language->id,
            ));
            return $this->display(__FILE__, 'views/templates/front/priceandorder_header.tpl');
        }
    }
    public function hookRightColumn($params)
    {
        if (version_compare(_PS_VERSION_, '1.5.0.0 ', '<')) {
            global $smarty, $cookie;
            if (Tools::isSubmit('submit2')) {
                $configPath = '../../config/config.inc.php';
                $email = Tools::getValue('email');
                $code = Tools::getValue('CaglarInputPO');
                $shipping_destination = Tools::getValue('shipping_destination');
                $name = Tools::getValue('product');
                $homedir = Tools::getValue('homedir');
                $paypal = Tools::getValue('paypal');
                $first_order = Tools::getValue('first_order');
                $language = Tools::getValue('language');
                $webMaster = Tools::getValue('recipient_address');
                $urgency = Tools::getValue('urgency');
                $splash_address = Tools::getValue('splash_address');
                $shop_logo = Tools::getValue('shop_logo');
                if (($code > 20) || !(is_numeric($code))) {
                    $this->_html .= '<a href="javascript:javascript:history.go(-1)">' . $this->displayError($this->l('You are not human, please go back and try once more.')) . '</a>';
                    return $this->_html;
                }
                global $cookie;
                global $smarty;
                $subject = $this->l('Price and Order E-mail');
                $subject_owner = $this->l('Quote Request Received');
                $template = 'priceandorder';
                $template_owner = 'priceandorder_owner';
                if (Shop::isFeatureActive()) {
                    $idShop = Shop::getContextShopID(true);
                    $idShopGroup = Shop::getContextShopGroupID(true);
                    $shop_name = Configuration::get('PS_SHOP_NAME', null, $idShopGroup, $idShop);
                    $shop_email = Configuration::get('PS_SHOP_EMAIL', null, $idShopGroup, $idShop);
                } else {
                    $shop_name = Configuration::get('PS_SHOP_NAME');
                    $shop_email = Configuration::get('PS_SHOP_EMAIL');
                }
                $template_vars = array(
                    '{shop_url}' => Tools::getShopDomain(true),
                    '{product}' => $name,
                    '{homedir}' => $homedir,
                    '{shipping_destination}' => $shipping_destination,
                    '{paypal}' => $paypal,
                    '{first_order}' => $first_order,
                    '{urgency}' => $urgency,
                    '{language}' => $language,
                    '{webmaster}' => $webMaster,
                    '{email}' => $email,
                    '{shop_name}' => $shop_name,
                    '{shop_logo}' => $shop_logo,
                    '{owner_email}' => $shop_email);
                if ((Mail::Send($language, $template, $shop_name, $template_vars, $email, null, null, null, null, null, dirname(__FILE__) . '/mails14/')) and (Mail::Send($language, $template_owner, $shop_name, $template_vars, explode(",", $webMaster), null, null, null, null, null, dirname(__FILE__) . '/mails14/'))) {
                    $smarty->assign('confirmation3', 1);
                } else {
                    $this->_html .= '<a href="javascript:javascript:history.go(-1)">' . $this->displayError($this->l('CAPTCHA Error, please go back and try once more.')) . '</a>';
                    return $this->_html;
                }
            }
            return $this->display(__FILE__, 'views/templates/front/blockpriceandorder14.tpl');
        } else {
            $priceandorder = new PriceandorderClass(1, $this->context->language->id);
            $this->smarty->assign(array(
                'priceandorder' => $priceandorder,
                'default_lang' => (int) $this->context->language->id,
                'id_lang' => $this->context->language->id,
                'base_dir' => Context::getContext()->shop->getBaseURL(true),
                'img_ps_dir' => _PS_CORE_IMG_DIR_,
            ));
            if (Tools::isSubmit('submit1')) {
                $configPath = '../../config/config.inc.php';
                $priceandorder_contactaddress = Tools::getValue('priceandorder_contactaddress');
                $priceandorder_contacttown = Tools::getValue('priceandorder_contacttown');
                $email = Tools::getValue('email');
                $code = Tools::getValue('CaglarInputPO1');
                $shipping_destination = Tools::getValue('shipping_destination');
                $name = Tools::getValue('product');
                $homedir = Tools::getValue('homedir');
                $paypal = Tools::getValue('paypal');
                $first_order = Tools::getValue('first_order');
                $language = Tools::getValue('language');
                $phone = Tools::getValue('phone');
                $name_cust = Tools::getValue('name_cust');
                $shipping_quantity = Tools::getValue('shipping_quantity');
                $webMaster = Tools::getValue('recipient_address');
                $urgency = Tools::getValue('urgency');
                $shop_logo = Tools::getValue('shop_logo');
                $errors = array();
                if (($code > 20) || !(is_numeric($code))) {
                    $this->_html .= '<a href="javascript:javascript:history.go(-1)">' . $this->displayError($this->l('You are not human, please go back and try once more.')) . '</a>';
                    return $this->_html;
                }
                $subject = $this->l('Price and Order E-mail');
                $subject_owner = $this->l('Quote Request Received');
                $template = 'priceandorder';
                $template_owner = 'priceandorder_owner';
                if (Shop::isFeatureActive()) {
                    $idShop = Shop::getContextShopID(true);
                    $idShopGroup = Shop::getContextShopGroupID(true);
                    $shop_name = Configuration::get('PS_SHOP_NAME', null, $idShopGroup, $idShop);
                    $shop_email = Configuration::get('PS_SHOP_EMAIL', null, $idShopGroup, $idShop);
                } else {
                    $shop_name = Configuration::get('PS_SHOP_NAME');
                    $shop_email = Configuration::get('PS_SHOP_EMAIL');
                }
                $template_vars = array(
                    '{priceandorder_contactaddress}' => $priceandorder_contactaddress,
                    '{priceandorder_contacttown}' => $priceandorder_contacttown,
                    '{shop_url}' => Tools::getShopDomain(true),
                    '{product}' => $name,
                    '{homedir}' => $homedir,
                    '{shipping_destination}' => $shipping_destination,
                    '{phone}' => $phone,
                    '{name_cust}' => $name_cust,
                    '{shipping_quantity}' => $shipping_quantity,
                    '{paypal}' => $paypal,
                    '{first_order}' => $first_order,
                    '{urgency}' => $urgency,
                    '{language}' => $language,
                    '{webmaster}' => $webMaster,
                    '{email}' => $email,
                    '{shop_url}' => Tools::getShopDomain(true),
                    '{shop_name}' => $shop_name,
                    '{shop_logo}' => $shop_logo,
                    '{owner_email}' => $shop_email);
                if ((Mail::Send($language, $template, $shop_name, $template_vars, $email, null, null, null, null, null, dirname(__FILE__) . '/mails/')) and (Mail::Send($language, $template_owner, $shop_name, $template_vars, explode(",", $webMaster), null, null, null, null, null, dirname(__FILE__) . '/mails/'))) {
                    $this->smarty->assign('confirmation1', 1);
                } else {
                    $this->_html .= '<a href="javascript:javascript:history.go(-1)">' . $this->displayError($this->l('CAPTCHA Error, please go back and try once more.')) . '</a>';
                    return $this->_html;
                }
            }
            if (version_compare(_PS_VERSION_, '1.6.0.0 ', '<')) {
                return $this->display(__FILE__, 'views/templates/front/blockpriceandorder16.tpl');
            } else {
                return $this->display(__FILE__, 'views/templates/front/blockpriceandorder17.tpl');
            }
        }
    }
    public function hookDisplayLeftColumn($params)
    {
        return $this->hookRightColumn($params);
    }
    public function hookDisplayRightColumn($params)
    {
        return $this->hookRightColumn($params);
    }
}
