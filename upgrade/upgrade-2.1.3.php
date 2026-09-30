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
 * 2.1.2 -> 2.1.3: security fixes plus upload-folder .htaccess (PAO-2 residual).
 * Force-writes the protective .htaccess so upgrades from ≤2.1.2 get it even
 * when the directory already existed without one.
 *
 * @param Module $module
 *
 * @return bool
 */
function upgrade_module_2_1_3($module)
{
    if ($module instanceof Priceandorder) {
        $dir = _PS_MODULE_DIR_ . $module->name . '/' . Priceandorder::UPLOAD_DIR;
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (is_dir($dir) && is_writable($dir)) {
            @file_put_contents($dir . '.htaccess', Priceandorder::uploadHtaccessContents());
        }
        $module->ensureUploadDirectory();
    }

    return true;
}
