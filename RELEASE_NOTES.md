# Quote Request Pro - Ask For a Custom Price — Release notes

_Only customer-relevant changes are listed. Internal refactoring, tests and housekeeping are omitted._

## 2.1.6

### New
- With multistore active, the configuration page shows a notice in the All shops and shop group contexts, and refuses to save settings, change a request's status or delete a request there, because those belong to a single shop. Pick one shop in the shop selector to do them

### Improved
- The logo in the quote e-mails is now read explicitly for the shop the request was sent from, like the shop name and the sender address already were

### Fixed
- Multistore only: opening the configuration page switched the back-office shop context to All shops and left it there for the rest of the request. The previous shop or group context is now restored, also after install

## 2.1.5

### New
- The configuration page links to the free MEG Venture Updates module, which installs updates for this module in one click. The line is hidden once that module is installed

## 2.1.4

### Fixed
- Admin quote and settings actions ignored GET. Toggle status, delete, and settings save now require `POST`. The Requests tab uses small POST forms instead of query-string links, so a crafted admin URL or browser prefetch cannot change or delete a request
- Storefront link and image URLs are HTML-escaped in the quote form (and matching admin preview/dashboard URLs), so a merchant-saved `http(s)` value that contains a quote cannot break out of an `href`/`src` attribute
- Quote submissions must be POST. A GET with `priceandorder_submit` is rejected
- Visitor fields cannot inject mail headers or HTML. Single-line fields strip CR/LF/NUL; notification mail variables are HTML-escaped before send
