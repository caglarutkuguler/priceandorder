<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * One row per shop: form field toggles, recipient e-mail, links and the
 * optional promotional image shown on the quote-request form.
 */
class PriceandorderClass extends ObjectModel
{
    public $id_shop;

    public $show_name;
    public $show_address;
    public $show_town;
    public $show_phone;
    public $show_quantity;
    public $show_destination;
    public $show_urgency;
    public $show_paypal;
    public $show_first_order;
    public $show_floating_button;

    public $recmail;
    public $promo_image;

    public $more_info_link;
    public $terms_conditions_link;
    public $privacy_policy_link;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'priceandorder',
        'primary' => 'id_priceandorder',
        'multilang' => true,
        'fields' => [
            'id_shop' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true],
            'show_name' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_address' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_town' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_phone' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_quantity' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_destination' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_urgency' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_paypal' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_first_order' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'show_floating_button' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'recmail' => ['type' => self::TYPE_STRING, 'size' => 255],
            'promo_image' => ['type' => self::TYPE_STRING, 'size' => 255],
            // Lang fields
            'more_info_link' => ['type' => self::TYPE_STRING, 'lang' => true, 'size' => 255],
            'terms_conditions_link' => ['type' => self::TYPE_STRING, 'lang' => true, 'size' => 255],
            'privacy_policy_link' => ['type' => self::TYPE_STRING, 'lang' => true, 'size' => 255],
        ],
    ];

    /**
     * @return PriceandorderClass|false
     */
    public static function getByIdShop($id_shop)
    {
        $id = (int) Db::getInstance()->getValue(
            'SELECT `id_priceandorder` FROM `' . _DB_PREFIX_ . 'priceandorder` WHERE `id_shop` = ' . (int) $id_shop
        );

        return $id ? new PriceandorderClass($id) : false;
    }
}
