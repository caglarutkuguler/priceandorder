{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture
* @license   All rights reserved
*}
<div class="po-requests">
    <div class="po-filters">
        <a href="{$po_current_index}&token={$po_token}&po_tab=requests" class="po-filter{if $po_status_filter == ''} po-filter--active{/if}">{l s='All' mod='priceandorder'}</a>
        <a href="{$po_current_index}&token={$po_token}&po_tab=requests&po_status=new" class="po-filter{if $po_status_filter == 'new'} po-filter--active{/if}">{l s='New' mod='priceandorder'}</a>
        <a href="{$po_current_index}&token={$po_token}&po_tab=requests&po_status=handled" class="po-filter{if $po_status_filter == 'handled'} po-filter--active{/if}">{l s='Handled' mod='priceandorder'}</a>
        <span class="po-total">{l s='%d request(s)' sprintf=[$po_total] mod='priceandorder'}</span>
    </div>

    {if $po_quotes|@count == 0}
        <p class="po-empty">{l s='No quote requests yet.' mod='priceandorder'}</p>
    {else}
        <div class="po-table-wrap">
            <table class="po-table">
                <thead>
                    <tr>
                        <th>{l s='Date' mod='priceandorder'}</th>
                        <th>{l s='Product' mod='priceandorder'}</th>
                        <th>{l s='Customer' mod='priceandorder'}</th>
                        <th>{l s='Contact' mod='priceandorder'}</th>
                        <th>{l s='Status' mod='priceandorder'}</th>
                        <th>{l s='Actions' mod='priceandorder'}</th>
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$po_quotes item=quote}
                        <tr>
                            <td>{$quote.date_add|date_format:'%Y-%m-%d %H:%M'}</td>
                            <td class="po-cell-product">{$quote.product|truncate:120|escape:'html':'UTF-8'}</td>
                            <td>{$quote.customer_name|escape:'html':'UTF-8'}</td>
                            <td>
                                <a href="mailto:{$quote.email|escape:'html':'UTF-8'}">{$quote.email|escape:'html':'UTF-8'}</a>
                                {if $quote.phone}<br>{$quote.phone|escape:'html':'UTF-8'}{/if}
                            </td>
                            <td>
                                {if $quote.status == 1}
                                    <span class="po-badge po-badge--handled">{l s='Handled' mod='priceandorder'}</span>
                                {else}
                                    <span class="po-badge po-badge--new">{l s='New' mod='priceandorder'}</span>
                                {/if}
                            </td>
                            <td class="po-cell-actions">
                                <a href="{$po_current_index}&token={$po_token}&po_tab=requests&priceandorderToggleStatus&id_priceandorder_quote={$quote.id_priceandorder_quote}">
                                    {if $quote.status == 1}{l s='Mark as new' mod='priceandorder'}{else}{l s='Mark handled' mod='priceandorder'}{/if}
                                </a>
                                &middot;
                                <a href="{$po_current_index}&token={$po_token}&po_tab=requests&deletepriceandorder_quote&id_priceandorder_quote={$quote.id_priceandorder_quote}"
                                   data-po-confirm="{l s='Delete this quote request?' mod='priceandorder'}">
                                    {l s='Delete' mod='priceandorder'}
                                </a>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>

        {if $po_pages > 1}
            <div class="po-pagination">
                {for $p=1 to $po_pages}
                    <a href="{$po_current_index}&token={$po_token}&po_tab=requests&po_status={$po_status_filter}&po_page={$p}" class="po-page{if $p == $po_page} po-page--active{/if}">{$p}</a>
                {/for}
            </div>
        {/if}
    {/if}
</div>
