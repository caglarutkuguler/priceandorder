{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture & Consulting Ltd.
* @license   https://opensource.org/licenses/MIT MIT License
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
                                <a href="#"
                                   data-po-view
                                   data-date="{$quote.date_add|date_format:'%Y-%m-%d %H:%M'|escape:'html':'UTF-8'}"
                                   data-status="{if $quote.status == 1}{l s='Handled' mod='priceandorder'}{else}{l s='New' mod='priceandorder'}{/if}"
                                   data-customer_name="{$quote.customer_name|escape:'html':'UTF-8'}"
                                   data-email="{$quote.email|escape:'html':'UTF-8'}"
                                   data-phone="{$quote.phone|escape:'html':'UTF-8'}"
                                   data-address="{$quote.address|escape:'html':'UTF-8'}"
                                   data-town="{$quote.town|escape:'html':'UTF-8'}"
                                   data-quantity="{$quote.quantity|escape:'html':'UTF-8'}"
                                   data-destination="{$quote.destination|escape:'html':'UTF-8'}"
                                   data-urgent="{if $quote.urgent}1{/if}"
                                   data-has_paypal="{if $quote.has_paypal}1{/if}"
                                   data-first_order="{if $quote.first_order}1{/if}"
                                   data-ip="{$quote.ip_address|escape:'html':'UTF-8'}"
                                   data-product="{$quote.product|escape:'html':'UTF-8'}">
                                    {l s='View' mod='priceandorder'}
                                </a>
                                &middot;
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

        <div class="po-view-modal" id="po-view-modal" hidden>
            <div class="po-view-backdrop" data-po-view-close></div>
            <div class="po-view-dialog">
                <div class="po-view-header">
                    <h4>{l s='Quote request details' mod='priceandorder'}</h4>
                    <button type="button" class="po-view-close" data-po-view-close aria-label="{l s='Close' mod='priceandorder'}">&times;</button>
                </div>
                <div class="po-view-body">
                    <div class="po-view-badges" data-po-view-badges>
                        <span class="po-badge po-badge--flag" data-po-view-flag="urgent">{l s='Urgent' mod='priceandorder'}</span>
                        <span class="po-badge po-badge--flag" data-po-view-flag="has_paypal">{l s='Has a PayPal account' mod='priceandorder'}</span>
                        <span class="po-badge po-badge--flag" data-po-view-flag="first_order">{l s='First-time customer' mod='priceandorder'}</span>
                    </div>

                    <div class="po-view-row"><span class="po-view-label">{l s='Date' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="date"></span></div>
                    <div class="po-view-row"><span class="po-view-label">{l s='Status' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="status"></span></div>
                    <div class="po-view-row"><span class="po-view-label">{l s='Customer' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="customer_name"></span></div>
                    <div class="po-view-row"><span class="po-view-label">{l s='E-mail address' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="email"></span></div>
                    <div class="po-view-row" data-po-view-row="phone"><span class="po-view-label">{l s='Phone number' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="phone"></span></div>
                    <div class="po-view-row" data-po-view-row="address"><span class="po-view-label">{l s='Address' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="address"></span></div>
                    <div class="po-view-row" data-po-view-row="town"><span class="po-view-label">{l s='Town / City' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="town"></span></div>
                    <div class="po-view-row" data-po-view-row="quantity"><span class="po-view-label">{l s='Quantity needed' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="quantity"></span></div>
                    <div class="po-view-row" data-po-view-row="destination"><span class="po-view-label">{l s='Shipping destination' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="destination"></span></div>
                    <div class="po-view-row"><span class="po-view-label">{l s='IP address' mod='priceandorder'}</span><span class="po-view-value" data-po-view-field="ip"></span></div>

                    <div class="po-view-product">
                        <span class="po-view-label">{l s='Product' mod='priceandorder'}</span>
                        <div class="po-view-product-text" data-po-view-field="product"></div>
                    </div>
                </div>
            </div>
        </div>
    {/if}
</div>
