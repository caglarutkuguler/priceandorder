# priceandorder — manual test checklist

PrestaShop **1.7 / 8 / 9**. Module **2.1.3**.

## Security fixes in 2.1.3

### Admin mutations (PAO-1)

- [ ] GET `…&configure=priceandorder&priceandorderToggleStatus&id_priceandorder_quote=N&token=…` does **not** change status
- [ ] GET `…&deletepriceandorder_quote&id_priceandorder_quote=N&token=…` does **not** delete
- [ ] Inbox “Mark handled” / “Mark as new” / “Delete” still work via the POST buttons
- [ ] Settings save still works (POST form)

### Promo upload (PAO-2)

- [ ] JPG / PNG / GIF / WEBP under 4 MB still upload and display under the form
- [ ] Renamed non-image (e.g. `.php` with image bytes renamed away) is rejected
- [ ] SVG / executable extension is rejected
- [ ] Remove-image checkbox still deletes the stored file
- [ ] `modules/priceandorder/views/img/uploads/.htaccess` exists after install/upgrade
- [ ] Deleting `.htaccess` then opening Configure recreates it
- [ ] Direct request to a planted `.php` under uploads is denied on Apache (403 / engine off); images still load

### FO output encoding (PAO-3)

- [ ] Terms / privacy / more-info links with `&` still navigate correctly
- [ ] A deliberately saved link containing `"` does **not** break out of the `href` attribute on the storefront
- [ ] Promo image still renders

### Quote submit + mail (PAO-4 / PAO-5)

- [ ] GET `…/module/priceandorder/quote?priceandorder_submit=1&…` does **not** create a quote
- [ ] Normal AJAX POST submit still succeeds (after waiting ≥3 s for the form token)
- [ ] Name containing a newline is stored without CR/LF
- [ ] Admin + customer notification mails still readable; HTML special chars show as text, not markup
- [ ] Sixth POST from same IP within an hour is refused

## Regression

- [ ] Sidebar column form + floating button still work
- [ ] Logged-in customer skips name/email fields; guest still sees them when enabled
- [ ] Dashboard “new quote” card still links to the Requests tab
- [ ] `php tests/QuoteFieldLimitsTest.php` passes
- [ ] `php tests/SecurityGuardsTest.php` passes
- [ ] `php tests/ReviewNudgeTest.php` passes

## Explicit non-findings (do not regress)

- [ ] Form token still HMAC(`timestamp`, `_COOKIE_KEY_`) with `hash_equals` + min/max age
- [ ] Quote SQL still casts `id_shop` / LIMIT / status; IP filter still uses `pSQL`
- [ ] Ads widget URL remains the hardcoded megventure.com endpoint (not merchant input)
- [ ] Promo delete path still uses `basename()`
