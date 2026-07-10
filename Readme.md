# Quote Request Pro - Ask For a Custom Price

*(module technical name: `priceandorder`, formerly "Price and Order - Column Quote Form")*

Adds a quote-request form to your PrestaShop storefront — a sidebar block and a
floating "Ask for a quote" button — so shoppers can ask for a custom price on
any product instead of leaving without one. Requests are e-mailed to you and
kept in a simple, searchable list in the back office, so nothing gets lost
even if an e-mail bounces.

Compatible with **PrestaShop 1.7 through 9.x**. Older versions (1.4-1.6) are
no longer supported as of v2.0.0 — see [Upgrading from 1.x](#upgrading-from-1x).

## Why this helps

- **Turns "I couldn't find a price" into a lead.** Custom quoting, made-to-order,
  or B2B-style catalogs often can't show a fixed price. This form captures the
  visitor's interest right there instead of losing them.
- **The floating button reaches mobile visitors too.** Many themes hide the
  sidebar column on small screens; the floating button doesn't depend on it.
- **Every request is kept**, even if your outgoing e-mail has a problem that
  day — check the Quote Requests tab as a safety net for your inbox.
- **Ask for what you actually need.** Turn off any field you don't care about
  (address, phone, quantity, urgency...) so the form stays short and
  completion stays high — shorter forms convert better.

## Requirements

- PrestaShop 1.7.0 or later (1.7, 8, 9 all supported)
- PHP 7.1+
- Outgoing e-mail configured in your shop (Advanced Parameters > E-mail)

## Quick start

1. Install the module and open its configuration page.
2. In **Settings**, enter the e-mail address(es) that should receive quote
   requests (comma-separated for more than one).
3. Choose which optional fields to ask for (address, phone, quantity, shipping
   destination, urgency, PayPal question, first-order question).
4. Go to **Design > Positions**, find `priceandorder` and make sure it's
   attached to a **Left column** or **Right column** hook (sidebar block) and
   to **Footer** (floating button) — installing the module already does this,
   this step matters if you've customized your theme's hooks.
5. Submit a test request from the storefront and confirm it shows up in the
   **Quote Requests** tab and in your mailbox.

## How it works

- The sidebar block and the floating button use the exact same form; a
  visitor can use whichever is visible to them.
- Logged-in customers are never asked for their name or e-mail — their
  account details are used automatically. Guests are always asked for both.
- The Terms & Conditions link falls back to your shop's own Terms &
  Conditions CMS page automatically if you leave it blank. The Privacy Policy
  link is simply not shown if left blank.
- The optional promotional image is uploaded from the Settings tab (no need
  to know a file path) and is automatically resized to fit 400x280 px without
  distortion.
- **Spam protection is automatic and invisible**: a hidden trap field plus a
  short delay/expiry check silently reject scripted submissions, and no more
  than 5 requests per hour are accepted from the same visitor. There is no
  CAPTCHA for a real visitor to solve.

## Marketing suggestions

- Point the "Learn more" link at a short page explaining your quoting process
  (typical turnaround time, how pricing works) — it reduces "will anyone
  actually reply?" hesitation.
- Use the promotional image slot for your best-selling or highest-margin
  product; it's the last thing shown before the visitor leaves the form.
- If your shop already runs live chat or a callback-request module, keep the
  copy consistent ("Ask for a quote" vs. "Request a callback") so visitors
  understand which one to use for what.
- Review the Quote Requests tab weekly even if replies happen by e-mail — it
  doubles as a lightweight log of demand for products you don't stock yet.

## Troubleshooting

**The form does not appear on the storefront.**
Open Design > Positions, search for `priceandorder`, and make sure it's
attached to a column hook (sidebar) or the footer hook (floating button), and
that your current theme actually renders that hook (some themes remove the
left/right column entirely).

**Quote request e-mails aren't arriving.**
Double-check the recipient address in Settings, check your spam folder, and
confirm outgoing e-mail works at all (test another module, or Advanced
Parameters > E-mail). Every request is saved in the Quote Requests tab
regardless of e-mail delivery, so you can always find it there.

**A visitor says the form rejected their submission.**
This is almost always the automatic spam protection: the form was submitted
faster than 3 seconds after the page loaded, more than an hour after the page
loaded, or more than 5 times in one hour from the same connection. Ask them
to reload the page and try again.

**The Terms & Conditions / Privacy Policy links are missing or wrong.**
Set them explicitly in Settings. An empty Terms link automatically falls back
to your shop's own Terms & Conditions CMS page; an empty Privacy link is
simply not shown.

**The promotional image looks stretched or gets rejected.**
Uploads are limited to JPG/PNG/GIF/WEBP up to 4 MB and are resized to fit
within 400x280 px while keeping proportions — the file itself is never
distorted, only fit within that box.

## Upgrading from 1.x

Versions before 2.0.0 stored each field toggle as a literal `Display:block` /
`display:none` CSS string, supported PrestaShop 1.4-1.6, and had no way to
review a submitted quote request other than the e-mail itself. Upgrading to
2.0.0 through the back office automatically:

- migrates your existing per-field toggles to the new schema,
- carries over your recipient e-mail address,
- adds the new Quote Requests log table,
- cleans up leftover configuration keys from very old (pre-1.5) installs.

The 1.4/1.5/1.6 compatibility code, the old client-side "math CAPTCHA", and
the bundled PDF user guide have all been removed — this Readme and the
in-module Tutorial & Help tab replace the PDF.

## Support

Questions, feedback or feature requests: **info@megventure.com**
