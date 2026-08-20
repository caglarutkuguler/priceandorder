{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture & Consulting Ltd.
* @license   https://opensource.org/licenses/MIT MIT License
*}
<div class="po-tutorial">
    <h3>{l s='Getting started' mod='priceandorder'}</h3>
    <ol class="po-steps">
        <li>{l s='Open the Settings tab and enter the e-mail address(es) that should receive quote requests.' mod='priceandorder'}</li>
        <li>{l s='Choose which optional fields to ask visitors for (address, phone, quantity...).' mod='priceandorder'}</li>
        <li>{l s='Go to Design > Positions in your back office and make sure Priceandorder is enabled on the "Left column" or "Right column" hook to show the sidebar form, and on "Footer" for the floating button.' mod='priceandorder'}</li>
        <li>{l s='Submit a test request from your storefront and check that it arrives in the Quote Requests tab and in your mailbox.' mod='priceandorder'}</li>
    </ol>

    <h3>{l s='How it works' mod='priceandorder'}</h3>
    <ul class="po-list">
        <li>{l s='The sidebar block and the floating button share the same form; a visitor can use either.' mod='priceandorder'}</li>
        <li>{l s='Logged-in customers are never asked for their name or e-mail &mdash; their account details are used automatically.' mod='priceandorder'}</li>
        <li>{l s='Every submission is saved in the Quote Requests tab even if the notification e-mail fails to deliver, so nothing gets lost.' mod='priceandorder'}</li>
        <li>{l s='Spam protection is automatic: a hidden trap field and a short delay check silently block scripted submissions, and no more than 5 requests per hour are accepted from the same visitor. There is nothing for a real visitor to solve.' mod='priceandorder'}</li>
        <li>{l s='A notification card appears on your Dashboard whenever there are new requests, with a direct link to this Quote Requests tab. It disappears once every new request is marked handled or deleted.' mod='priceandorder'}</li>
    </ul>

    <h3>{l s='Troubleshooting' mod='priceandorder'}</h3>
    <dl class="po-faq">
        <dt>{l s='The form does not appear on the storefront.' mod='priceandorder'}</dt>
        <dd>{l s='Open Design > Positions in the back office, search for "priceandorder" and make sure it is attached to a column hook (sidebar) or the footer hook (floating button), and that your current theme actually displays that hook (some themes remove the left/right column).' mod='priceandorder'}</dd>

        <dt>{l s='I am not receiving quote request e-mails.' mod='priceandorder'}</dt>
        <dd>{l s='Check the recipient address in Settings for typos, check your spam folder, and confirm that outgoing e-mail works at all by testing another module or Advanced Parameters > E-mail. Every request is saved in the Quote Requests tab regardless, so you can always find it there.' mod='priceandorder'}</dd>

        <dt>{l s='A visitor says the form rejected their submission.' mod='priceandorder'}</dt>
        <dd>{l s='This is almost always the automatic spam protection: submitting the form faster than 3 seconds after the page loads, submitting it more than an hour after the page loaded, or more than 5 requests in one hour from the same connection. Ask them to reload the page and try again.' mod='priceandorder'}</dd>

        <dt>{l s='The Terms & Conditions / Privacy Policy links are missing or wrong.' mod='priceandorder'}</dt>
        <dd>{l s='Set them explicitly in Settings. If the Terms link is left empty, your shop\'s own Terms & Conditions CMS page is used automatically; if the Privacy link is left empty, it is simply not shown.' mod='priceandorder'}</dd>

        <dt>{l s='My promotional image looks stretched or is rejected.' mod='priceandorder'}</dt>
        <dd>{l s='Uploads are limited to JPG, PNG, GIF or WEBP files up to 4 MB and are automatically resized to fit within 400x280 px while keeping their proportions, so the file itself is never distorted.' mod='priceandorder'}</dd>

        <dt>{l s='The Dashboard notification card is not showing up.' mod='priceandorder'}</dt>
        <dd>{l s='Check Design > Positions for the dashboardZoneOne hook and make sure priceandorder is attached and enabled there as well &mdash; it is controlled separately from the storefront form, and only appears on the actual Dashboard page. The card only shows while at least one request is still marked "New".' mod='priceandorder'}</dd>
    </dl>

    <p class="po-support">
        {l s='Need more help? Contact' mod='priceandorder'}
        <a href="mailto:info@megventure.com">info@megventure.com</a>
    </p>
</div>
