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
 * Migrates a pre-2.0.0 install (PS1.4-1.6 era schema: one 'Display:block' /
 * 'display:none' CSS-string column per toggle, recmail/most_ordered stored
 * per-language for no reason) to the 2.0.0 schema, and adds the new
 * quote-request log table. Safe to run on a fresh install too (no-op).
 */
function upgrade_module_2_0_0($module)
{
    $db = Db::getInstance();

    if (!$db->getValue('SHOW TABLES LIKE \'' . _DB_PREFIX_ . 'priceandorder\'')) {
        return true;
    }

    $mainColumns = array_column($db->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'priceandorder`'), 'Field');
    $isLegacySchema = in_array('customername', $mainColumns, true);

    if ($isLegacySchema) {
        $db->execute(
            'ALTER TABLE `' . _DB_PREFIX_ . 'priceandorder`
            ADD COLUMN `show_name` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_address` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_town` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_phone` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_quantity` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_destination` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_urgency` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `show_paypal` tinyint(1) unsigned NOT NULL DEFAULT 0,
            ADD COLUMN `show_first_order` tinyint(1) unsigned NOT NULL DEFAULT 0,
            ADD COLUMN `show_floating_button` tinyint(1) unsigned NOT NULL DEFAULT 1,
            ADD COLUMN `recmail_new` varchar(255) NOT NULL DEFAULT \'\',
            ADD COLUMN `promo_image` varchar(255) NOT NULL DEFAULT \'\''
        );

        $toggleMap = [
            'customername' => 'show_name',
            'contactaddress' => 'show_address',
            'contacttown' => 'show_town',
            'phone' => 'show_phone',
            'quantity' => 'show_quantity',
            'destination' => 'show_destination',
            'urgency' => 'show_urgency',
            'paypal' => 'show_paypal',
            'first_order' => 'show_first_order',
        ];
        foreach ($toggleMap as $oldColumn => $newColumn) {
            $db->execute(
                'UPDATE `' . _DB_PREFIX_ . 'priceandorder`
                SET `' . $newColumn . '` = IF(LOWER(`' . $oldColumn . '`) LIKE \'%block%\', 1, 0)'
            );
        }

        // recmail/most_ordered used to be stored once per language for a
        // value that never actually varied by language; keep the default
        // language's value only.
        $langColumns = array_column($db->executeS('SHOW COLUMNS FROM `' . _DB_PREFIX_ . 'priceandorder_lang`'), 'Field');
        if (in_array('recmail', $langColumns, true)) {
            $defaultLang = (int) Configuration::get('PS_LANG_DEFAULT');
            $rows = $db->executeS(
                'SELECT `id_priceandorder`, `recmail` FROM `' . _DB_PREFIX_ . 'priceandorder_lang`
                WHERE `id_lang` = ' . $defaultLang
            );
            foreach ($rows as $row) {
                $recmail = trim((string) $row['recmail']);
                if ($recmail !== '' && strtolower($recmail) !== 'demo@demo.com') {
                    $db->execute(
                        'UPDATE `' . _DB_PREFIX_ . 'priceandorder`
                        SET `recmail_new` = \'' . pSQL($recmail) . '\'
                        WHERE `id_priceandorder` = ' . (int) $row['id_priceandorder']
                    );
                }
            }
        }

        $dropCandidates = [
            'contactaddress', 'contacttown', 'contactemail', 'destination', 'quantity',
            'customername', 'phone', 'captcha', 'urgency', 'paypal', 'first_order', 'recmail',
        ];
        $dropClauses = [];
        foreach ($dropCandidates as $column) {
            if (in_array($column, $mainColumns, true)) {
                $dropClauses[] = 'DROP COLUMN `' . $column . '`';
            }
        }
        $dropClauses[] = 'CHANGE COLUMN `recmail_new` `recmail` varchar(255) NOT NULL DEFAULT \'\'';
        $db->execute('ALTER TABLE `' . _DB_PREFIX_ . 'priceandorder` ' . implode(', ', $dropClauses));

        $dropLangClauses = [];
        foreach (['most_ordered', 'recmail'] as $column) {
            if (in_array($column, $langColumns, true)) {
                $dropLangClauses[] = 'DROP COLUMN `' . $column . '`';
            }
        }
        if ($dropLangClauses) {
            $db->execute('ALTER TABLE `' . _DB_PREFIX_ . 'priceandorder_lang` ' . implode(', ', $dropLangClauses));
        }
    }

    // New in 2.0.0, needed whether or not the schema above was legacy.
    $db->execute(
        'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'priceandorder_quote` (
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
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8'
    );

    // Leftover flat Configuration keys from ancient pre-1.5 installs that
    // uninstall() never had a chance to clean up (module was upgraded, not
    // reinstalled, on the way from 1.4 to 1.7+).
    foreach (['recmail', 'infolink', 'urgency', 'paypal', 'first_order', 'destination', 'piclink', 'captcha'] as $suffix) {
        Configuration::deleteByName('priceandorder_' . $suffix);
    }

    if (!$module->registerHook('displayFooter')) {
        return false;
    }

    return true;
}
