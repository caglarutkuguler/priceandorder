# Changelog

All notable changes to **Quote Request Pro - Ask For a Custom Price** (`priceandorder`).

## 2.1.3

Security hardening and stability improvements. Recommended for all shops.

This is a maintenance release: no feature changes and no configuration changes.
Existing settings and saved quote requests are kept, and the upgrade step runs
automatically when the module is updated.

## 2.1.2

### Fixed

- **A long phone number, address or town could lose the whole request.** The
  form's `maxlength` attributes are a convenience for the visitor, not a
  limit: anything posting straight at the module's own controller could send
  a field of any length. The row was then wider than its column, `ObjectModel`
  refused it, and the visitor was told to "please try again" while a genuine
  request went nowhere. Every posted string is now cut to the width of its own
  column before the row is built -- name and address and town and destination
  at 255 characters, phone and quantity at 64, the product description at 2000
  as before. The cut counts characters, not bytes, so accented and non-Latin
  text is not left half-written.
- **The phone field promised more than the column could hold.** Its
  `maxlength` said 70 where the column is 64.
- **A refused save left no trace.** When the row cannot be written, the
  failure is now recorded in Advanced Parameters, Logs with the shop and the
  e-mail address, instead of only showing the visitor a generic message.

### Tests

- `tests/QuoteFieldLimitsTest.php` - seventeen checks over the field limits:
  every column width, an untouched short value, tag stripping and trimming, a
  missing field, multi-byte text cut by characters and still valid UTF-8, and
  the form's own `maxlength` attributes measured against the columns. Plain
  PHP, no PrestaShop and no database.

## 2.1.1

### Fixed

- **Two lines of the Tutorial & Help tab showed a literal `&mdash;`.** The
  source strings and all eight translations spelled the dash as an HTML
  entity (the Dutch one as a broken `-mdash;`), and PrestaShop escapes module translations once more when it
  renders them, so the entity itself reached the screen instead of a dash.
  They now use the real character.

## 2.1.0

### Added

- A single review-request line on the module's own configuration page. It
  appears at the earliest 21 days after installing, asks once for a short
  review on megventure.com, and disappears forever after a click, a
  "No thanks", or three unanswered views. It makes no outbound request of any
  kind and stores nothing beyond three prefixed configuration values, which
  uninstalling removes.

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
