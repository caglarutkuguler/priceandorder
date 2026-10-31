# Quote Request Pro - Ask For a Custom Price — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 2.1.4

### Improved
- `TEST_CHECKLIST.md` — manual verification for 2.1.4

### Fixed
- Admin quote and settings actions ignored GET. Toggle status, delete, and settings save now require `POST`. The Requests tab uses small POST forms instead of query-string links, so a crafted admin URL or browser prefetch cannot change or delete a request
- Storefront link and image URLs are HTML-escaped in the quote form (and matching admin preview/dashboard URLs), so a merchant-saved `http(s)` value that contains a quote cannot break out of an `href`/`src` attribute
- Quote submissions must be POST. A GET with `priceandorder_submit` is rejected
- Visitor fields cannot inject mail headers or HTML. Single-line fields strip CR/LF/NUL; notification mail variables are HTML-escaped before send

## 2.1.3

### Improved
- Security hardening and stability improvements. Recommended for all shops
- This is a maintenance release: no feature changes and no configuration changes. Existing settings and saved quote requests are kept, and the upgrade step runs automatically when the module is updated

## 2.1.2

### Fixed
- The phone field promised more than the column could hold. Its `maxlength` said 70 where the column is 64
- A refused save left no trace. When the row cannot be written, the failure is now recorded in Advanced Parameters, Logs with the shop and the e-mail address, instead of only showing the visitor a generic message
