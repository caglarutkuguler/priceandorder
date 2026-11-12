<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * Multistore edge cases of 2.1.6. Plain PHP, no shop:
 *
 *   php tests/MultistoreTest.php
 *
 * The context switch/restore methods are lifted from the real module source
 * and run against a Shop stub that behaves like core's static context.
 */
if (PHP_SAPI === 'cli' && !defined('_PS_VERSION_')) {
    define('_PS_VERSION_', '8.1.0'); // standalone run outside PrestaShop
}
if (!defined('_PS_VERSION_')) {
    exit;
}

/** Mirrors Shop::setContext() / getContext*() of 1.7.0 up to 9.x. */
class Shop
{
    const CONTEXT_SHOP = 1;
    const CONTEXT_GROUP = 2;
    const CONTEXT_ALL = 4;
    public static $type = 1;
    public static $idShop = 3;
    public static $idGroup = 2;
    public static $groupOf = array(3 => 2, 4 => 2, 5 => 9);
    public static function setContext($type, $id = null)
    {
        if ($type == self::CONTEXT_ALL) {
            self::$idShop = null;
            self::$idGroup = null;
        } elseif ($type == self::CONTEXT_GROUP) {
            self::$idShop = null;
            self::$idGroup = (int) $id;
        } else {
            self::$idShop = (int) $id;
            self::$idGroup = self::$groupOf[$id];
        }
        self::$type = $type;
    }
    public static function getContext()
    {
        return self::$type;
    }
    public static function getContextShopID()
    {
        return self::$idShop;
    }
    public static function getContextShopGroupID()
    {
        return self::$idGroup;
    }
}

$fail = 0;
function ok($cond, $label)
{
    global $fail;
    echo ($cond ? '  ok   ' : '  FAIL ') . $label . "\n";
    if (!$cond) {
        ++$fail;
    }
}

$src = (string) file_get_contents(dirname(__DIR__) . '/priceandorder.php');
$src = str_replace("\r\n", "\n", $src);

echo "1) switch / restore, using the module's own methods\n";
preg_match('/    private function switchToAllShopsContext\(\)\n    \{.*?\n    \}\n/s', $src, $a);
preg_match('/    private function restoreShopContext\(array \$previous\)\n    \{.*?\n    \}\n/s', $src, $b);
ok(!empty($a[0]) && !empty($b[0]), 'both methods found in the module source');
eval('class Harness {' . $a[0] . $b[0] . ' public function run($f) { $p = $this->switchToAllShopsContext(); $in = Shop::getContext(); $f($in); $this->restoreShopContext($p); } }');
$h = new Harness();

Shop::setContext(Shop::CONTEXT_SHOP, 4);
$h->run(function ($in) {
    ok($in === Shop::CONTEXT_ALL, 'inside: All shops context');
});
ok(Shop::getContext() == Shop::CONTEXT_SHOP && Shop::getContextShopID() === 4 && Shop::getContextShopGroupID() === 2, 'single shop context restored (shop 4, group 2)');

Shop::setContext(Shop::CONTEXT_GROUP, 9);
$h->run(function ($in) {
});
ok(Shop::getContext() == Shop::CONTEXT_GROUP && Shop::getContextShopID() === null && Shop::getContextShopGroupID() === 9, 'group context restored (group 9)');

Shop::setContext(Shop::CONTEXT_ALL);
$h->run(function ($in) {
});
ok(Shop::getContext() == Shop::CONTEXT_ALL && Shop::getContextShopID() === null && Shop::getContextShopGroupID() === null, 'All shops context restored');

echo "\n2) Module source\n";
ok(preg_match("/version = '2\\.1\\.6'/", $src) === 1, 'version is 2.1.6');
ok(substr_count($src, 'Shop::setContext(Shop::CONTEXT_ALL)') === 2, 'setContext(CONTEXT_ALL) appears only in the switch and the restore helpers');
ok(preg_match('/function ensureUpToDate\(\)\n    \{\n        \$previousContext = Shop::isFeatureActive\(\) \? \$this->switchToAllShopsContext\(\) : null;\n        try \{\n            \$this->reassertHooksAndSchema\(\);\n        \} finally \{\n            if \(\$previousContext !== null\) \{\n                \$this->restoreShopContext\(\$previousContext\);/', $src) === 1, 'ensureUpToDate restores in finally, only when it switched');
ok(preg_match('/public function install\(\).*?try \{\n            return \$this->runInstall\(\);\n        \} finally \{\n            if \(\$previousContext !== null\) \{\n                \$this->restoreShopContext/s', $src) === 1, 'install restores in finally');
ok(strpos($src, 'return Shop::isFeatureActive() && Shop::getContext() != Shop::CONTEXT_SHOP;') !== false, 'notice/refusal only with multistore active outside single shop context');
ok(strpos($src, '$nudge . $this->renderShopContextNotice() . $content') !== false, 'configure page prints the notice');
ok(preg_match("/\\\$isPost && \\\$this->isMultiShopContext\\(\\)\n\\s+&& \\(Tools::isSubmit\\('submitPriceandorderSettings'\\)\n\\s+\\|\\| Tools::isSubmit\\('priceandorderToggleStatus'\\)\n\\s+\\|\\| Tools::isSubmit\\('deletepriceandorder_quote'\\)\\)/", $src) === 1, 'settings save, status toggle and delete are refused outside a single shop context');
ok(strpos($src, "elseif (\$isPost && Tools::isSubmit('submitPriceandorderSettings'))") !== false, 'the normal save path is still POST-gated');

echo "\n3) Quote e-mail logo is read for the request's shop\n";
$quote = (string) file_get_contents(dirname(__DIR__) . '/controllers/front/quote.php');
ok(strpos($quote, "Configuration::get('PS_LOGO', null, null, \$idShop)") !== false, 'PS_LOGO carries the shop id');
ok(strpos($quote, "Configuration::get('PS_LOGO')") === false, 'no context-only PS_LOGO read is left');

echo "\n4) Translations\n";
$keys = array(
    md5('You are in the All shops or a shop group context. Settings saved here become the default for every shop that has no value of its own. Per-shop content is edited one shop at a time, so pick a single shop in the shop selector first.'),
    md5('This action changes content that belongs to a single shop. Pick one shop in the shop selector first; nothing was saved.'),
);
foreach (glob(dirname(__DIR__) . '/translations/*.php') as $file) {
    if (basename($file) === 'index.php') {
        continue;
    }
    $text = (string) file_get_contents($file);
    foreach ($keys as $k) {
        ok(strpos($text, '<{priceandorder}prestashop>priceandorder_' . $k) !== false, basename($file) . ' has ' . substr($k, 0, 8));
    }
}

echo "\n" . ($fail === 0 ? "OK - all passed\n" : "$fail test(s) FAILED\n");
exit($fail === 0 ? 0 : 1);
