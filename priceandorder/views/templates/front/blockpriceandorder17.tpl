{*
*	Module Name: Price and Order - Column Quote Form
*	Module URI: Please contact with info@megventure.com
*	Description: A Price Quoting Module for Demanded Orders
*	Version: 1.9.2
*	Author: MEG Venture
*
*	Copyright 2011, MEG Venture (info@megventure.com)
*
*	This program is not a free software: you can't redistribute it and/or modify
*	it. All rights reserved.
*
*
*	This copyright notice  and licence should be retained in all modules based on this framework.
*	This does not affect your rights to assert copyright over your own original work.
*}

{* <script type="text/javascript" src="{$urls.base_url}/modules/priceandorder/views/js/gen_validatorv4.js" ></script> *}

<div class="block-priceandorder hidden-sm-down">
    <h4 class="text-uppercase h6 hidden-sm-down">{l s='ASK FOR A QUOTE' mod='priceandorder'}</h4>
    {include './form.tpl'}
</div> {* block-priceandorder hidden-sm-down *}

<!--  Hidden Footer -->
<div id="priceandorder_hidden_footer" class="col-md-2 links wrapper">
  <h3 class="priceandorder-title hidden-sm-down">{l s='ASK FOR A QUOTE' mod='priceandorder'}</h3>
  <div class="title clearfix hidden-md-up collapsed" data-target="#footer_priceandorder_gif" data-toggle="collapse" aria-expanded="false">
    <span class="h3">{l s='Get a Quote' mod='priceandorder'}</span>
    <span class="float-xs-right">
      <span class="navbar-toggler collapse-icons">
        <i class="material-icons add"></i>
        <i class="material-icons remove"></i>
      </span>
    </span>
  </div>
<div id="footer_priceandorder_gif" aria-expanded="false" class="collapse">
    {include './form.tpl'}
</div> {* footer_priceandorder_gif *}
</div> {* priceandorder_hidden_footer *}
<!--  Hidden Footer -->

<script>
	$('.human').show();
	
    var a = Math.ceil(Math.random() * 10);
    var b = Math.ceil(Math.random() * 10);     
    var c = a + b

	$('.a').html(a);
	$('.b').html(b);
		
    function CaglarCodeCheck(){
		var d = parseInt($('.CaglarInput1').val());
    if (!d) {
      var d = parseInt($('.CaglarInput1').eq(1).val());
    }
    if (d == c) {
      $('.human').hide();
			n = "{l s='ORDER NOW' mod='priceandorder'}";
			$(".recaptcha_submit_replace").replaceWith("<p align='center' style='padding-top:20px;'><input type='submit' name='submit1' style='margin-top:10px;' value='" + n + "' class='button pricebutton btn btn-primary submit1'/></p>");
		}
		else
		{
      $('.human').show();
			$(".submit1").replaceWith("<input type='hidden' class='recaptcha_submit_replace'/>");
		}       
    }	

function clearText(thefield){
if (thefield.defaultValue==thefield.value)
	thefield.value = ""
} 

var frmvalidator = new Validator("form1");
frmvalidator.EnableMsgsTogether();

if(document.form1.product)
{
	frmvalidator.addValidation("product","req","{l s='Requested product description is required.' mod='priceandorder'}"); 
}

if(document.form1.name_cust)
{
	frmvalidator.addValidation("name_cust","req","{l s='Contact name is required.' mod='priceandorder'}"); 
}

if(document.form1.email)
{
	frmvalidator.addValidation("email","email","{l s='Please enter a valid e-mail address.' mod='priceandorder'}"); 
}
</script>