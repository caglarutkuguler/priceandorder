{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture
* @license   All rights reserved
*}
<div class="po-dash-card">
    <div class="po-dash-icon" aria-hidden="true">&#128172;</div>
    <div class="po-dash-body">
        {if $po_new_count == 1}
            {l s='You have 1 new quote request awaiting a reply.' mod='priceandorder'}
        {else}
            {l s='You have %d new quote requests awaiting a reply.' sprintf=[$po_new_count] mod='priceandorder'}
        {/if}
    </div>
    <a href="{$po_requests_url}" class="po-dash-btn">{l s='View quote requests' mod='priceandorder'}</a>
</div>
