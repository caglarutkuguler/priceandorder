<?php
/**
 * @author    MEG Venture <info@megventure.com>
 * @copyright 2007-2026 MEG Venture & Consulting Ltd.
 * @license   https://opensource.org/licenses/MIT MIT License
 *
 * Static regression checks for the 2.1.4 security fixes. Plain PHP, no shop.
 *
 *   php tests/SecurityGuardsTest.php
 */
if (PHP_SAPI !== 'cli') {
    exit;
}

$root = dirname(__DIR__);
$fail = 0;

function ok($cond, $label)
{
    global $fail;
    if ($cond) {
        echo "  ok   $label\n";

        return;
    }
    ++$fail;
    echo "  FAIL $label\n";
}

echo "1) Admin mutations are POST-gated\n";
$main = file_get_contents($root . '/priceandorder.php');
ok(strpos($main, "REQUEST_METHOD") !== false, 'REQUEST_METHOD check in module class');
ok(strpos($main, "\$isPost && Tools::isSubmit('priceandorderToggleStatus')") !== false
    || (strpos($main, 'priceandorderToggleStatus') !== false && strpos($main, '$isPost') !== false),
    'toggle status behind $isPost');
ok(strpos($main, "\$isPost && Tools::isSubmit('deletepriceandorder_quote')") !== false
    || (strpos($main, 'deletepriceandorder_quote') !== false && strpos($main, '$isPost') !== false),
    'delete behind $isPost');

echo "\n2) Promo upload allowlist uses image type bytes\n";
ok(strpos($main, 'is_uploaded_file') !== false, 'is_uploaded_file present');
ok(strpos($main, 'IMAGETYPE_JPEG') !== false, 'IMAGETYPE map present');
ok(strpos($main, "allowedExt = ['jpg', 'png', 'gif', 'webp']") !== false
    || strpos($main, "\$allowedExt = ['jpg', 'png', 'gif', 'webp']") !== false,
    'client extension allowlist');

echo "\n2b) Upload folder .htaccess deny-PHP hardening\n";
$htaccess = $root . '/views/img/uploads/.htaccess';
ok(is_file($htaccess), 'views/img/uploads/.htaccess present in package');
$ht = is_file($htaccess) ? file_get_contents($htaccess) : '';
ok(strpos($ht, 'php_flag engine off') !== false, 'php_flag engine off in .htaccess');
ok(strpos($ht, 'Require all denied') !== false, 'Apache 2.4 Require all denied');
ok(strpos($ht, 'Deny from all') !== false, 'Apache 2.2 Deny from all');
ok(strpos($main, 'function ensureUploadDirectory') !== false, 'ensureUploadDirectory helper');
ok(strpos($main, 'function uploadHtaccessContents') !== false, 'uploadHtaccessContents helper');
ok(is_file($root . '/upgrade/upgrade-2.1.4.php'), 'upgrade-2.1.4.php present');

echo "\n3) Front quote controller POST-only + header/mail helpers\n";
$quote = file_get_contents($root . '/controllers/front/quote.php');
ok(strpos($quote, 'REQUEST_METHOD') !== false, 'POST gate in quote controller');
ok(strpos($quote, 'function headerSafe') !== false, 'headerSafe helper');
ok(strpos($quote, 'function mailEscape') !== false, 'mailEscape helper');
ok(strpos($quote, 'htmlspecialchars') !== false, 'htmlspecialchars used for mail vars');

echo "\n4) FO template escapes merchant-controlled URLs\n";
$tpl = file_get_contents($root . '/views/templates/front/quoteform.tpl');
ok(strpos($tpl, "po_terms_link|escape:'html':'UTF-8'") !== false, 'terms link escaped');
ok(strpos($tpl, "po_privacy_link|escape:'html':'UTF-8'") !== false, 'privacy link escaped');
ok(strpos($tpl, "po_more_info_link|escape:'html':'UTF-8'") !== false, 'more-info link escaped');
ok(strpos($tpl, "po_promo_image_url|escape:'html':'UTF-8'") !== false, 'promo image URL escaped');
ok(strpos($tpl, "po_ajax_url|escape:'html':'UTF-8'") !== false, 'ajax URL escaped');

echo "\n5) Inbox actions are POST forms, not GET links\n";
$req = file_get_contents($root . '/views/templates/admin/_requests.tpl');
ok(strpos($req, 'name="priceandorderToggleStatus"') !== false, 'toggle is a POST button');
ok(strpos($req, 'name="deletepriceandorder_quote"') !== false, 'delete is a POST button');
ok(strpos($req, 'priceandorderToggleStatus&id_priceandorder_quote') === false, 'no GET toggle URL');

echo "\n" . ($fail === 0 ? "OK - all passed\n" : "$fail FAILED\n");
exit($fail === 0 ? 0 : 1);
