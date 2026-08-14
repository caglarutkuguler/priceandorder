# Changelog

All notable changes to **Quote Request Pro - Ask For a Custom Price** (`priceandorder`).

## 2.0.7

### Fixed

- **Dashboard notification card overflowed in longer languages.** The
  "View quote requests" button had `flex: none` with `white-space: nowrap`
  in a single flex row alongside the message text, so on narrower dashboard
  columns (and with longer translated strings, e.g. French "Voir les
  demandes de devis") the button refused to shrink and forced the text into
  a one-word-per-line column while the button itself overflowed the card,
  overlapping neighboring dashboard widgets. The icon+text group and the
  button are now separate flex items that wrap onto their own line when
  there isn't room for both, and the button gets an `ellipsis` safety net
  for the rare case a translation is wider than the available column.

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
