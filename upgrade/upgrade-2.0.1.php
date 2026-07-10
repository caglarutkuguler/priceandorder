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
 * Registers the new Dashboard notification hook for shops that installed
 * the module before it existed.
 */
function upgrade_module_2_0_1($module)
{
    if (!$module->isRegisteredInHook('displayDashboardTop')) {
        return $module->registerHook('displayDashboardTop');
    }

    return true;
}
