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
 * One row per submitted quote request. Kept so a merchant can find a request
 * again from the back office even if the notification e-mail is lost, and so
 * the front controller can rate-limit submissions per IP address.
 */
class PriceandorderQuoteClass extends ObjectModel
{
    const STATUS_NEW = 0;
    const STATUS_HANDLED = 1;

    public $id_shop;
    public $id_lang;
    public $product;
    public $customer_name;
    public $email;
    public $phone;
    public $address;
    public $town;
    public $quantity;
    public $destination;
    public $urgent;
    public $has_paypal;
    public $first_order;
    public $ip_address;
    public $status;
    public $date_add;
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'priceandorder_quote',
        'primary' => 'id_priceandorder_quote',
        'fields' => [
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'id_lang' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'product' => ['type' => self::TYPE_STRING, 'size' => 2000],
            'customer_name' => ['type' => self::TYPE_STRING, 'size' => 255],
            'email' => ['type' => self::TYPE_STRING, 'validate' => 'isEmail', 'size' => 255, 'required' => true],
            'phone' => ['type' => self::TYPE_STRING, 'size' => 64],
            'address' => ['type' => self::TYPE_STRING, 'size' => 255],
            'town' => ['type' => self::TYPE_STRING, 'size' => 255],
            'quantity' => ['type' => self::TYPE_STRING, 'size' => 64],
            'destination' => ['type' => self::TYPE_STRING, 'size' => 255],
            'urgent' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'has_paypal' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'first_order' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'ip_address' => ['type' => self::TYPE_STRING, 'size' => 45],
            'status' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'date_add' => ['type' => self::TYPE_DATE],
            'date_upd' => ['type' => self::TYPE_DATE],
        ],
    ];

    /**
     * Number of submissions received from the given IP address since $since.
     */
    public static function countRecentByIp($ipAddress, $since)
    {
        return (int) Db::getInstance()->getValue(
            'SELECT COUNT(`id_priceandorder_quote`) FROM `' . _DB_PREFIX_ . 'priceandorder_quote`
            WHERE `ip_address` = \'' . pSQL($ipAddress) . '\' AND `date_add` >= \'' . pSQL($since) . '\''
        );
    }
}
