# Changelog

All notable changes to **Quote Request Pro - Ask For a Custom Price** (`priceandorder`).

## 2.0.6

### Fixed

- **Upgrade could disable the module on shops still running a pre-2.0.1
  install.** The 2.0.1 upgrade step registered the Dashboard notification
  hook on `displayDashboardTop`, for which this class has never implemented
  a `hookDisplayDashboardTop()` method - `Hook::registerHook()` throws
  `PrestaShopModuleException` for that on `_PS_MODE_DEV_` shops, failing the
  upgrade step and disabling the module. `upgrade-2.0.2.php` already
  supersedes this (it unregisters `displayDashboardTop` and moves the
  notification to `dashboardZoneOne`, which the class does implement), so
  2.0.1's step is now a no-op. 2.0.2's own registration is also hardened to
  never let a live `registerHook()` call decide the step's success.
