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
 * Originally registered the Dashboard notification hook on
 * 'displayDashboardTop' for shops that installed the module before it
 * existed. No hookDisplayDashboardTop() method was ever shipped on this
 * class - Hook::registerHook() throws PrestaShopModuleException for that on
 * _PS_MODE_DEV_ shops, which fails this whole upgrade step and disables the
 * module. upgrade-2.0.2.php immediately supersedes this anyway, moving the
 * notification to 'dashboardZoneOne' (which the class does implement, via
 * hookDashboardZoneOne()) because 'displayDashboardTop' turned out to fire
 * on most admin pages showing a KPI row, not just the actual Dashboard. This
 * step is now a no-op left only so the version history stays intact.
 */
function upgrade_module_2_0_1($module)
{
    return true;
}
