{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture & Consulting Ltd.
* @license   https://opensource.org/licenses/MIT MIT License
*}
<div class="po-admin" id="priceandorder-admin">
    <div class="panel">
        <div class="panel-heading">
            <img src="{$po_module_dir}logo.png" alt="" width="24" height="24"> {l s='Quote Request Pro' mod='priceandorder'}
        </div>

        <div class="po-tabs" role="tablist">
            <button type="button" class="po-tab{if $po_active_tab == 'settings'} po-tab--active{/if}" data-po-tab="settings">{l s='Settings' mod='priceandorder'}</button>
            <button type="button" class="po-tab{if $po_active_tab == 'requests'} po-tab--active{/if}" data-po-tab="requests">{l s='Quote Requests' mod='priceandorder'}</button>
            <button type="button" class="po-tab{if $po_active_tab == 'tutorial'} po-tab--active{/if}" data-po-tab="tutorial">{l s='Tutorial & Help' mod='priceandorder'}</button>
        </div>

        <div class="po-tab-panel" data-po-panel="settings"{if $po_active_tab != 'settings'} hidden{/if}>
            {$po_settings_html nofilter}
        </div>
        <div class="po-tab-panel" data-po-panel="requests"{if $po_active_tab != 'requests'} hidden{/if}>
            {$po_requests_html nofilter}
        </div>
        <div class="po-tab-panel" data-po-panel="tutorial"{if $po_active_tab != 'tutorial'} hidden{/if}>
            {$po_tutorial_html nofilter}
        </div>
    </div>
</div>
