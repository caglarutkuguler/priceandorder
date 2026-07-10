{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture
* @license   All rights reserved
*}
<form action="{$po_current_index}&token={$po_token}" method="post" enctype="multipart/form-data" class="po-settings-form">
    <input type="hidden" name="po_tab" value="settings">

    {$po_form_output nofilter}

    <fieldset class="po-fieldset">
        <legend>{l s='Form fields to ask for' mod='priceandorder'}</legend>
        <p class="po-fieldset-desc">{l s='The product description is always asked. E-mail is always asked from guests; logged-in customers never see it, their account e-mail is used automatically.' mod='priceandorder'}</p>

        <div class="po-toggle-grid">
            <label class="po-switch-row">
                <input type="checkbox" name="show_name" value="1"{if $po_settings->show_name} checked{/if}>
                <span class="po-switch"></span>
                {l s='Customer name (guests only)' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_address" value="1"{if $po_settings->show_address} checked{/if}>
                <span class="po-switch"></span>
                {l s='Address' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_town" value="1"{if $po_settings->show_town} checked{/if}>
                <span class="po-switch"></span>
                {l s='Town / City' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_phone" value="1"{if $po_settings->show_phone} checked{/if}>
                <span class="po-switch"></span>
                {l s='Phone number' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_quantity" value="1"{if $po_settings->show_quantity} checked{/if}>
                <span class="po-switch"></span>
                {l s='Quantity needed' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_destination" value="1"{if $po_settings->show_destination} checked{/if}>
                <span class="po-switch"></span>
                {l s='Shipping destination' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_urgency" value="1"{if $po_settings->show_urgency} checked{/if}>
                <span class="po-switch"></span>
                {l s='Order urgency' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_paypal" value="1"{if $po_settings->show_paypal} checked{/if}>
                <span class="po-switch"></span>
                {l s='Ask if they already have a PayPal account' mod='priceandorder'}
            </label>
            <label class="po-switch-row">
                <input type="checkbox" name="show_first_order" value="1"{if $po_settings->show_first_order} checked{/if}>
                <span class="po-switch"></span>
                {l s='Ask if this is their first order' mod='priceandorder'}
            </label>
        </div>
    </fieldset>

    <fieldset class="po-fieldset">
        <legend>{l s='Notifications & links' mod='priceandorder'}</legend>

        <div class="po-group">
            <label for="po-recmail">{l s='Quote recipient e-mail(s)' mod='priceandorder'} <span class="po-required">*</span></label>
            <input type="text" id="po-recmail" name="recmail" value="{$po_settings->recmail|escape:'html':'UTF-8'}" maxlength="255">
            <p class="po-help">{l s='Where new quote requests are sent. Separate multiple addresses with commas.' mod='priceandorder'}</p>
        </div>

        {if $po_languages|@count > 1}
            <div class="po-group po-lang-switch-wrap">
                <label for="po-lang-switch">{l s='Editing links for language' mod='priceandorder'}</label>
                <select id="po-lang-switch">
                    {foreach from=$po_languages item=language}
                        <option value="{$language.id_lang}"{if $language.id_lang == $po_default_lang} selected{/if}>{$language.name}</option>
                    {/foreach}
                </select>
            </div>
        {/if}

        <div class="po-group">
            <label>{l s='Learn more link' mod='priceandorder'}</label>
            {foreach from=$po_languages item=language}
                <input type="text" class="po-lang-field" data-po-lang="{$language.id_lang}"{if $language.id_lang != $po_default_lang} hidden{/if}
                    name="more_info_link_{$language.id_lang}"
                    value="{$po_settings->more_info_link[$language.id_lang]|escape:'html':'UTF-8'}" maxlength="255"
                    placeholder="https://...">
            {/foreach}
            <p class="po-help">{l s='Optional link shown under the form, e.g. a page explaining how quotes work.' mod='priceandorder'}</p>
        </div>

        <div class="po-group">
            <label>{l s='Terms & Conditions link' mod='priceandorder'}</label>
            {foreach from=$po_languages item=language}
                <input type="text" class="po-lang-field" data-po-lang="{$language.id_lang}"{if $language.id_lang != $po_default_lang} hidden{/if}
                    name="terms_conditions_link_{$language.id_lang}"
                    value="{$po_settings->terms_conditions_link[$language.id_lang]|escape:'html':'UTF-8'}" maxlength="255"
                    placeholder="https://...">
            {/foreach}
            <p class="po-help">{l s='Leave empty to automatically use your shop\'s own Terms & Conditions page.' mod='priceandorder'}</p>
        </div>

        <div class="po-group">
            <label>{l s='Privacy Policy link' mod='priceandorder'}</label>
            {foreach from=$po_languages item=language}
                <input type="text" class="po-lang-field" data-po-lang="{$language.id_lang}"{if $language.id_lang != $po_default_lang} hidden{/if}
                    name="privacy_policy_link_{$language.id_lang}"
                    value="{$po_settings->privacy_policy_link[$language.id_lang]|escape:'html':'UTF-8'}" maxlength="255"
                    placeholder="https://...">
            {/foreach}
            <p class="po-help">{l s='Leave empty to hide this link.' mod='priceandorder'}</p>
        </div>
    </fieldset>

    <fieldset class="po-fieldset">
        <legend>{l s='Extra features' mod='priceandorder'}</legend>

        <div class="po-toggle-grid">
            <label class="po-switch-row">
                <input type="checkbox" name="show_floating_button" value="1"{if $po_settings->show_floating_button} checked{/if}>
                <span class="po-switch"></span>
                {l s='Floating "Ask for a quote" button on every page' mod='priceandorder'}
            </label>
        </div>
        <p class="po-help">{l s='Useful since the sidebar block is often hidden on small screens; the floating button works on mobile too.' mod='priceandorder'}</p>

        <div class="po-group">
            <label for="po-promo-image">{l s='Promotional image' mod='priceandorder'}</label>
            {if $po_promo_image_url}
                <div class="po-promo-preview">
                    <img src="{$po_promo_image_url}" alt="">
                    <label class="po-checkbox"><input type="checkbox" name="remove_promo_image" value="1"> {l s='Remove this image' mod='priceandorder'}</label>
                </div>
            {/if}
            <input type="file" id="po-promo-image" name="promo_image" accept="image/png,image/jpeg,image/gif,image/webp">
            <p class="po-help">{l s='Optional image (e.g. your best-selling product) shown under the form. Leave empty to hide this block.' mod='priceandorder'}</p>
        </div>
    </fieldset>

    <div class="po-form-actions">
        <button type="submit" name="submitPriceandorderSettings" class="po-btn po-btn--primary">{l s='Save' mod='priceandorder'}</button>
    </div>
</form>
