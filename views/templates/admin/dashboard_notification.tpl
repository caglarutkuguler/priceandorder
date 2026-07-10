{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture
* @license   All rights reserved
*}
{* The Dashboard renders its hook zones via a separate AJAX call after the
   page header has already been sent, so a normal addCSS()-enqueued
   stylesheet never makes it into <head> in time. Styling is inlined here
   instead, scoped to .po-dash-card, so it always applies regardless of how
   this fragment gets inserted into the page. *}
<style>
.po-dash-card {
    display: flex;
    align-items: center;
    gap: .9rem;
    background: #fff6e5;
    border: 1px solid #f5c26b;
    border-radius: 4px;
    padding: .9rem 1.1rem;
    margin: 0 0 1.5rem;
    font-size: .9rem;
    color: #6a4a06;
    font-family: inherit;
}
.po-dash-card .po-dash-icon {
    font-size: 1.4rem;
    line-height: 1;
    flex: none;
}
.po-dash-card .po-dash-body {
    flex: 1 1 auto;
}
.po-dash-card .po-dash-btn {
    flex: none;
    display: inline-block;
    padding: .5rem 1rem;
    background: #da0f00;
    color: #fff !important;
    border-radius: 4px;
    font-size: .82rem;
    font-weight: 600;
    text-decoration: none;
    white-space: nowrap;
}
.po-dash-card .po-dash-btn:hover {
    opacity: .9;
}
</style>
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
