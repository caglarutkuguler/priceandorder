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

class PriceandorderClass extends ObjectModel
{
    public $id;
    public $id_shop;
    public $contactaddress;
    public $contacttown;
    public $contactemail;
    public $destination;
    public $quantity;
    public $customername;
    public $phone;
    public $captcha;
    public $urgency;
    public $paypal;
    public $first_order;
    public $more_info_link;
    public $terms_conditions_link;
    public $privacy_policy_link;
    public $most_ordered;
    public $recmail;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'priceandorder',
        'primary' => 'id_priceandorder',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt', 'required' => true),
            'contactaddress' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'contacttown' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'contactemail' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'destination' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'quantity' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'customername' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'phone' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'captcha' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'urgency' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'paypal' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            'first_order' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
            // Lang fields
            'more_info_link' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'terms_conditions_link' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'privacy_policy_link' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'most_ordered' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
            'recmail' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isString'),
        ),
    );

    public function add($autodate = true, $null_values = false)
    {
        return parent::add($autodate, $null_values);
    }

    public static function getByIdShop($id_shop)
    {
        $id = Db::getInstance()->getValue('SELECT `id_priceandorder` FROM `' . _DB_PREFIX_ . 'priceandorder` WHERE `id_shop` =' . (int) $id_shop);
        if ($id) {
            return new PriceandorderClass((int) $id);
        } else {
            return false;
        }
    }

    public function copyFromPost()
    {
        /* Classical fields */
        $admin_infos = array("contactaddress" => Tools::getValue('contactaddress'), "contacttown" => Tools::getValue('contacttown'), "contactemail" => Tools::getValue('contactemail'), "destination" => Tools::getValue('destination'), "quantity" => Tools::getValue('quantity'), "customername" => Tools::getValue('customername'), "phone" => Tools::getValue('phone'), "captcha" => Tools::getValue('captcha'), "urgency" => Tools::getValue('urgency'), "paypal" => Tools::getValue('paypal'), "first_order" => Tools::getValue('first_order'));
        //print_r ($admin_infos);

        foreach ($admin_infos as $key => $value) {
            if (key_exists($key, $this) and $key != 'id_' . $this->table) {
                $this->{$key} = $value;
            }
        }

        /* Multilingual fields */
        if (sizeof($this->fieldsValidateLang)) {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                foreach ($this->fieldsValidateLang as $field => $validation) {
                    if (Tools::getValue($field . '_' . (int) ($language['id_lang'])) != false) {
                        $this->{$field}[(int) ($language['id_lang'])] = Tools::getValue($field . '_' . (int) ($language['id_lang']));
                    }
                }
            }
        }
    }
}
