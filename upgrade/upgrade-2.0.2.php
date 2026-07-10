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
 * v2.0.1 registered the Dashboard notification card on `displayDashboardTop`,
 * which turned out to fire on most admin pages showing a KPI row (e.g.
 * "Performance"), not just the actual Dashboard. Moves it to
 * `dashboardZoneOne`, which core's own dashboard widget modules
 * (dashactivity, dashtrends, dashgoals...) confirm is Dashboard-only.
 */
function upgrade_module_2_0_2($module)
{
    if ($module->isRegisteredInHook('displayDashboardTop')) {
        $module->unregisterHook('displayDashboardTop');
    }

    if (!$module->isRegisteredInHook('dashboardZoneOne')) {
        return $module->registerHook('dashboardZoneOne');
    }

    return true;
}
