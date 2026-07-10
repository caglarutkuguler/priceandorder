{*
* @author    MEG Venture <info@megventure.com>
* @copyright 2007-2026 MEG Venture
* @license   All rights reserved
*}
<button type="button" class="po-fab" data-po-open aria-haspopup="dialog" aria-controls="po-modal">
    <span class="po-fab-icon" aria-hidden="true">&#128172;</span>
    <span class="po-fab-label">{l s='Ask for a quote' mod='priceandorder'}</span>
</button>

<div class="po-modal" id="po-modal" role="dialog" aria-modal="true" aria-labelledby="po-modal-title" hidden>
    <div class="po-modal-backdrop" data-po-close></div>
    <div class="po-modal-dialog">
        <div class="po-modal-header">
            <h4 id="po-modal-title">{l s='Ask for a quote' mod='priceandorder'}</h4>
            <button type="button" class="po-modal-close" data-po-close aria-label="{l s='Close' mod='priceandorder'}">&times;</button>
        </div>
        <div class="po-modal-body">
            {$po_form_html nofilter}
        </div>
    </div>
</div>
