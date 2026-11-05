# Quote Request Pro - Ask For a Custom Price — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 2.1.5

### New
- The configuration page links to the free MEG Venture Updates module, which installs updates for this module in one click. The line is hidden once that module is installed

## 2.1.4

### Fixed
- Admin quote and settings actions ignored GET. Toggle status, delete, and settings save now require `POST`. The Requests tab uses small POST forms instead of query-string links, so a crafted admin URL or browser prefetch cannot change or delete a request
- Storefront link and image URLs are HTML-escaped in the quote form (and matching admin preview/dashboard URLs), so a merchant-saved `http(s)` value that contains a quote cannot break out of an `href`/`src` attribute
- Quote submissions must be POST. A GET with `priceandorder_submit` is rejected
- Visitor fields cannot inject mail headers or HTML. Single-line fields strip CR/LF/NUL; notification mail variables are HTML-escaped before send

## 2.1.3

### Improved
- Security hardening and stability improvements. Recommended for all shops
- This is a maintenance release: no feature changes and no configuration changes. Existing settings and saved quote requests are kept, and the upgrade step runs automatically when the module is updated
