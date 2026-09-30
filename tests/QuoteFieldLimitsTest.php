<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * Guards the server-side field limits of the quote controller.
 *
 * The maxlength attributes on the form are a convenience for the visitor.
 * Anything posting straight at the controller can send a field of any length,
 * and before 2.1.2 an over-long phone number or address made ObjectModel
 * refuse the whole row, so a genuine request was lost behind a generic
 * "please try again". cleanField() now cuts every posted string to the width
 * of its own column.
 *
 * Plain PHP: no PrestaShop bootstrap, no database.
 *
 *   php tests/QuoteFieldLimitsTest.php
 */
if (!defined('_PS_VERSION_')) {
    // Only the CLI harness may run without the shop; a web hit exits here.
    if (PHP_SAPI !== 'cli') {
        exit;
    }
    define('_PS_VERSION_', '8.1.0');
}

$posted = [];

class Tools
{
    public static function getValue($key, $default = false)
    {
        global $posted;

        return isset($posted[$key]) ? $posted[$key] : $default;
    }

    public static function strlen($str)
    {
        return function_exists('mb_strlen') ? mb_strlen($str, 'UTF-8') : strlen($str);
    }

    public static function substr($str, $start, $length = null)
    {
        if (function_exists('mb_substr')) {
            return $length === null
                ? mb_substr($str, $start, null, 'UTF-8')
                : mb_substr($str, $start, $length, 'UTF-8');
        }

        return $length === null ? substr($str, $start) : substr($str, $start, $length);
    }
}

class ModuleFrontController
{
}

class PrestaShopLogger
{
    public static function addLog()
    {
    }
}

require_once dirname(__DIR__) . '/controllers/front/quote.php';

$failures = 0;
$checks = 0;

function check($label, $got, $expected)
{
    global $failures, $checks;
    ++$checks;
    if ($got === $expected) {
        return;
    }
    ++$failures;
    echo "FAIL  $label\n      expected: " . var_export($expected, true) . "\n      got:      " . var_export($got, true) . "\n";
}

$controller = (new ReflectionClass('PriceandorderQuoteModuleFrontController'))->newInstanceWithoutConstructor();
$clean = new ReflectionMethod('PriceandorderQuoteModuleFrontController', 'cleanField');
$clean->setAccessible(true);

function cleanField($controller, $method, $name, $max)
{
    return $method->invoke($controller, $name, $max);
}

// 1. The column widths the controller must respect.
$columns = [
    'product' => 2000,
    'customer_name' => 255,
    'phone' => 64,
    'address' => 255,
    'town' => 255,
    'quantity' => 64,
    'destination' => 255,
];

foreach ($columns as $field => $max) {
    $posted = [$field => str_repeat('a', $max + 500)];
    $out = cleanField($controller, $clean, $field, $max);
    check("$field is cut to $max", Tools::strlen($out), $max);
}

// 2. A value that fits is returned untouched.
$posted = ['town' => 'Kadikoy'];
check('short value untouched', cleanField($controller, $clean, 'town', 255), 'Kadikoy');

// 3. Whitespace is trimmed and tags are stripped.
$posted = ['address' => "  <b>Bahce</b> sokak 12  "];
check('tags stripped and trimmed', cleanField($controller, $clean, 'address', 255), 'Bahce sokak 12');

// 4. A missing field becomes an empty string, never false.
$posted = [];
check('missing field is an empty string', cleanField($controller, $clean, 'phone', 64), '');

// 5. Multi-byte text is cut by characters, not by bytes, so the column
//    still accepts it and the last character is not left half-written.
$posted = ['town' => str_repeat("\xc3\xbc", 100)];      // 100 x u-umlaut
$out = cleanField($controller, $clean, 'town', 64);
check('multi-byte cut by characters', Tools::strlen($out), 64);
check('multi-byte stays valid UTF-8', (bool) preg_match('//u', $out), true);

// 6. The form's own maxlength attributes must not promise more than the
//    columns can hold -- that mismatch is what hid the bug.
$tpl = file_get_contents(dirname(__DIR__) . '/views/templates/front/quoteform.tpl');
foreach (['phone' => 64, 'quantity' => 64, 'address' => 255, 'town' => 255, 'destination' => 255] as $field => $max) {
    if (preg_match('/name="' . $field . '"[^>]*maxlength="(\d+)"/', $tpl, $m)) {
        check("form maxlength for $field is not above $max", (int) $m[1] <= $max, true);
    }
}

echo $failures === 0
    ? "OK - all passed ($checks checks)\n"
    : "FAILED - $failures of $checks checks\n";

exit($failures === 0 ? 0 : 1);
