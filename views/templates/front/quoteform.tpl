{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture
* @license   All rights reserved
*}
<div class="po-form" data-context="{$po_context}">
    {if $po_flash_success}
        <div class="po-alert po-alert--success">{l s='Thank you! Your quote request has been sent. We will get back to you shortly.' mod='priceandorder'}</div>
    {elseif $po_flash_error}
        <div class="po-alert po-alert--error">{$po_flash_error|escape:'html':'UTF-8'}</div>
    {/if}
    <div class="po-alert" data-po-alert hidden></div>

    <form action="{$po_ajax_url}" method="post" class="po-fields" data-po-form>
        <input type="hidden" name="priceandorder_token" value="{$po_token}">
        <input type="hidden" name="priceandorder_submit" value="1">

        {* Honeypot: real visitors never see this, bots that auto-fill every field do. *}
        <div class="po-hp" aria-hidden="true">
            <label for="po-hp-{$po_context}">{l s='Leave this field empty' mod='priceandorder'}</label>
            <input type="text" id="po-hp-{$po_context}" name="priceandorder_confirm_email" tabindex="-1" autocomplete="off">
        </div>

        <p class="po-intro">
            {if $po_is_logged}
                {l s='Hi %s! Tell us what you are looking for and we will get back to you with a price.' sprintf=[$po_customer_name] mod='priceandorder'}
            {else}
                {l s='Tell us what you are looking for and we will get back to you with a price.' mod='priceandorder'}
            {/if}
        </p>

        <div class="po-group">
            <label for="po-product-{$po_context}">{l s='Product name, model or link' mod='priceandorder'} <span class="po-required">*</span></label>
            <textarea id="po-product-{$po_context}" name="product" rows="4" required maxlength="2000"></textarea>
        </div>

        {if !$po_is_logged}
            {if $po_settings->show_name}
                <div class="po-group">
                    <label for="po-name-{$po_context}">{l s='Full name' mod='priceandorder'} <span class="po-required">*</span></label>
                    <input type="text" id="po-name-{$po_context}" name="customer_name" maxlength="70" required>
                </div>
            {/if}
            <div class="po-group">
                <label for="po-email-{$po_context}">{l s='E-mail address' mod='priceandorder'} <span class="po-required">*</span></label>
                <input type="email" id="po-email-{$po_context}" name="email" maxlength="255" required>
            </div>
        {/if}

        {if $po_settings->show_address}
            <div class="po-group">
                <label for="po-address-{$po_context}">{l s='Address' mod='priceandorder'}</label>
                <input type="text" id="po-address-{$po_context}" name="address" maxlength="255">
            </div>
        {/if}

        {if $po_settings->show_town}
            <div class="po-group">
                <label for="po-town-{$po_context}">{l s='Town / City' mod='priceandorder'}</label>
                <input type="text" id="po-town-{$po_context}" name="town" maxlength="255">
            </div>
        {/if}

        {if $po_settings->show_phone}
            <div class="po-group">
                <label for="po-phone-{$po_context}">{l s='Phone number' mod='priceandorder'}</label>
                <input type="tel" id="po-phone-{$po_context}" name="phone" maxlength="70">
            </div>
        {/if}

        {if $po_settings->show_quantity}
            <div class="po-group">
                <label for="po-quantity-{$po_context}">{l s='Quantity needed' mod='priceandorder'}</label>
                <input type="text" id="po-quantity-{$po_context}" name="quantity" maxlength="64">
            </div>
        {/if}

        {if $po_settings->show_destination}
            <div class="po-group">
                <label for="po-destination-{$po_context}">{l s='Shipping destination' mod='priceandorder'}</label>
                <input type="text" id="po-destination-{$po_context}" name="destination" maxlength="255">
            </div>
        {/if}

        {if $po_settings->show_urgency}
            <div class="po-group po-inline">
                <span class="po-inline-label">{l s='How urgent is this?' mod='priceandorder'}</span>
                <label class="po-radio"><input type="radio" name="urgent" value="1" checked> {l s='Urgent' mod='priceandorder'}</label>
                <label class="po-radio"><input type="radio" name="urgent" value="0"> {l s='Not urgent' mod='priceandorder'}</label>
            </div>
        {/if}

        {if $po_settings->show_paypal}
            <div class="po-group">
                <label class="po-checkbox"><input type="checkbox" name="has_paypal" value="1"> {l s='I already have a PayPal account' mod='priceandorder'}</label>
            </div>
        {/if}

        {if $po_settings->show_first_order}
            <div class="po-group">
                <label class="po-checkbox"><input type="checkbox" name="first_order" value="1"> {l s='This would be my first order' mod='priceandorder'}</label>
            </div>
        {/if}

        <div class="po-group">
            <label class="po-checkbox">
                <input type="checkbox" name="priceandorder_consent" value="1" required>
                {if $po_terms_link || $po_privacy_link}
                    {l s='I agree to the' mod='priceandorder'}
                    {if $po_terms_link}<a href="{$po_terms_link}" target="_blank" rel="noopener">{l s='Terms & Conditions' mod='priceandorder'}</a>{/if}
                    {if $po_terms_link && $po_privacy_link}{l s='and' mod='priceandorder'}{/if}
                    {if $po_privacy_link}<a href="{$po_privacy_link}" target="_blank" rel="noopener">{l s='Privacy Policy' mod='priceandorder'}</a>{/if}
                {else}
                    {l s='I agree that this website may use my information to reply to this request.' mod='priceandorder'}
                {/if}
                <span class="po-required">*</span>
            </label>
        </div>

        <p class="po-required-note">{l s='* required field' mod='priceandorder'}</p>

        <button type="submit" class="po-submit">{l s='Ask for a quote' mod='priceandorder'}</button>
    </form>

    {if $po_more_info_link || $po_promo_image_url}
        <div class="po-promo">
            {if $po_promo_image_url}<img src="{$po_promo_image_url}" alt="{l s='Our best offer' mod='priceandorder'}">{/if}
            {if $po_more_info_link}<a href="{$po_more_info_link}" target="_blank" rel="noopener">{l s='Learn more' mod='priceandorder'}</a>{/if}
        </div>
    {/if}
</div>
