{*
*	Module Name: Price & Order
*	Module URI: Please contact with https://addons.prestashop.com/en/contact-form.php
*	Description: A Price Quoting Module for Demanded Orders
*	Version: 0.1.9.2
*	Author: MEG Venture
*
*	Copyright 2012, MEG Venture (https://addons.prestashop.com/en/contact-form.php)
*
*	This program is not a free software: you can't redistribute it and/or modify
*	it. All rights reserved to Caglar Utku GULER
*
*
*	This copyright notice  and licence should be retained in all modules based on this framework.
*	This does not affect your rights to assert copyright over your own original work.
*}

<div class="block">
	<h4>{l s='PRICE & ORDER' mod='priceandorder'}</h4>
	<div class="block_content" style="padding:0px;"> 

<script>

function clearText(thefield){
if (thefield.defaultValue==thefield.value)
thefield.value = ""
} 
</script>

<form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" name="form1" id="form1">
{if isset($confirmation3)}

<div style="background:#3F6; padding:5px; border:thin; border-color:#030; text-align:center">{l s='Your quote request has been successfully sent to our team.' mod='priceandorder'}
</div><br />
{else}
{/if}
  <table border="0" cellspacing="5" cellpadding="6">
      {if $cookie->isLogged()}
    <tr>
      <td class="line_po">
		{$cookie->customer_firstname} {$cookie->customer_lastname},<br />{l s='If you want to get the best price for the product you looking for, please fill in the empty box below.' mod='priceandorder'}
		<div style="display:none"><p align="center">
          <input name="email" type="text" id="email" value="{$cookie->email}"  size="21" maxlength="70" />
        </p></div>
       </td>
    </tr> 
    <tr>
      <td class="line_po_center">
          <textarea name="product" cols="24" rows="5" id="product"></textarea>
          <br />{l s='Please write product`s name and model or website link *' mod='priceandorder'}
	</td>              
    </tr>
{if Configuration::get("priceandorder_destination")=="display:none"}
{else}
<tr>
<td colspan="2" class="line_po_center">
<div align="center" style="{Configuration::get("priceandorder_destination")}">
	<input size="21" name="shipping_destination" type="text" id="shipping_destination" value=""/><br>
    {l s='Enter shipping destination' mod='priceandorder'}
</div>
</td>
</tr>   
{/if}     
{if Configuration::get("priceandorder_urgency")=="display:none"}
{else}
<tr>    
<td class="line_po_center">
	<LABEL for="urg1">{l s=' Urgent' mod='priceandorder'}</LABEL>
    <input type="radio" name="urgency" id="urg1" value="{l s='- Urgent response needed' mod='priceandorder'}" checked="checked"/>

	<LABEL for="urg2">{l s=' Not Urgent' mod='priceandorder'}</LABEL>
	<input type="radio" name="urgency" id="urg2" value="{l s='- Urgent response is not needed' mod='priceandorder'}"/><br>
	<br>
</td>
</tr>   
{/if}

{if Configuration::get("priceandorder_paypal")=="display:none"}
&nbsp;
{else}
<tr>    
<td class="line_po_center">
<input name="paypal" type="checkbox" value="{l s='- Customer has a PayPal account' mod='priceandorder'}" />{l s=' I have PayPal account' mod='priceandorder'}
</td>
</tr>
{/if}

{if Configuration::get("priceandorder_first_order")=="display:none"}
&nbsp;
{else}
<tr>
<td class="line_po_center">
<input name="first_order" type="checkbox" value="{l s='- This will be the first order of the customer' mod='priceandorder'}" />{l s=' This is my first order' mod='priceandorder'}              
</td>
</tr>  
{/if}

{else}
    <tr>
      <td class="line_po">  
        {l s='Dear Visitor' mod='priceandorder'}, <br />{l s='If you want to get the best price for the product you looking for, please fill in the boxes below.' mod='priceandorder'}</td></tr>
        <tr>
      <td class="line_po_center">  
       <input name="email" type="text" id="email" value="{l s='Required for Contact' mod='priceandorder'}"  onFocus="clearText(this)" size="21" maxlength="70" /><sup> *</sup><br />
          {l s='E-mail Address' mod='priceandorder'}
      </td>
    </tr>
    <tr>
      <td class="line_po_center">
         <textarea name="product" cols="24"  rows="5" id="product"></textarea><br />
        {l s='Please write product`s name and model or website link *' mod='priceandorder'}
      </td>
    </tr>
{if Configuration::get("priceandorder_destination")=="display:none"}
{else}
<tr>
<td colspan="2" class="line_po_center">
<div align="center" style="{Configuration::get("priceandorder_destination")}">
	<input size="21" name="shipping_destination" type="text" id="shipping_destination" value=""/><br>
    {l s='Enter shipping destination' mod='priceandorder'}
</div>
</td>
</tr>   
{/if}     
    
{if Configuration::get("priceandorder_urgency")=="display:none"}
{else}
<tr>    
<td class="line_po_center">
	<LABEL for="urg1">{l s=' Urgent' mod='priceandorder'}</LABEL>
    <input type="radio" name="urgency" id="urg1" value="{l s='- Urgent response needed' mod='priceandorder'}" checked="checked"/>

	<LABEL for="urg2">{l s=' Not Urgent' mod='priceandorder'}</LABEL>
	<input type="radio" name="urgency" id="urg2" value="{l s='- Urgent response is not needed' mod='priceandorder'}"/><br>
	<br>
</td>
</tr>   
{/if}

{if Configuration::get("priceandorder_paypal")=="display:none"}
&nbsp;
{else}
<tr>    
<td class="line_po_center1">
<input name="paypal" type="checkbox" value="{l s='- Customer has a PayPal account' mod='priceandorder'}" />{l s=' I have PayPal account' mod='priceandorder'}
</td>
</tr>
{/if}

{if Configuration::get("priceandorder_first_order")=="display:none"}
&nbsp;
{else}
<tr>
<td class="line_po_center">
<input name="first_order" type="checkbox" value="{l s='- This will be the first order of the customer' mod='priceandorder'}" />{l s=' This is my first order' mod='priceandorder'}
</td>
</tr>  
{/if}
{/if}   
<tr><td class="line_po" style="padding-bottom:10px;"><sup>* </sup>{l s='denotes required fields' mod='priceandorder'} </td></tr>
       
    <tr>
      <td>
<!--Invisible Data Transfer Section-->      
          <input name="language" type="hidden" id="language" value="{(!isset($cookie) OR !is_object($cookie)) ? (int)(Configuration::get('PS_LANG_DEFAULT')) : (int)($cookie->id_lang)}"/>
          <input name="homedir" type="hidden" id="homedir" value="{$base_dir}"/>  
          <input name="recipient_address" type="hidden" id="recipient_address" value="{Configuration::get("priceandorder_recmail")}"/>
          <input name="shop_logo" type="hidden" id="shop_logo" value="{$img_ps_dir}logo.jpg"/>
<!--Invisible Data Transfer Section--> 

{if (Configuration::get("priceandorder_captcha")=="display:none") OR ($cookie->isLogged())}
<input id='CaglarInput' name='CaglarInput' type='hidden' value='10'/>
{else}       
<!--CAPTCHA Security Section-->
<div align="center" style="{$priceandorder->captcha}">
<div style="float:left;padding:0px 2px;">{l s='What is' mod='priceandorder'}</div><div id="a" style="float:left;padding:0px 2px;"></div><div style="float:left;padding:0px 2px;">+</div><div id="b" style="float:left;padding:0px 2px;clear:right;"></div><div style="float:left;padding:0px 2px;">?</div>
<input id='CaglarInput' name='CaglarInput' type='text' maxlength='2' size='2' style="width: 30px;float: left;clear: none;margin-top: -6px;height: 26px;text-align: center;"/>
<input id="Button1" type="button" value="{l s='Check' mod='priceandorder'}" onclick="CaglarCodeCheck()" style="width: 20%;float: left;clear: none;height: 26px;margin-top: -6px;"/>
</div>
<div id="human" style="text-align:center;width:90%;color:#F00;font-weight:bold;clear:both;">{l s='Proove that you are human to see the Submit button' mod='priceandorder'}</div>
<!--CAPTCHA Security Section-->
{/if} 
      </td>
    </tr>
<!--Submit Button-->
<tr><td class="line_po_center">
   <input type="hidden" id="recaptcha_submit_replace">
</td></tr>
<!--Submit Button-->          
<!--Reset Button-->          
<tr><td class="line_po_center">
<div align="center"><input type="reset" name="clear" id="clear" value="{l s='Reset Form' mod='priceandorder'}" class="exclusive" /></div>
</td></tr>
<!--Reset Button-->             
<tr><td class="line_po_center">
<a href="{Configuration::get("priceandorder_infolink")}">{l s='For More Information please click here' mod='priceandorder'}</a>
</td></tr>
<tr><td class="line_po_center1">{l s=' MOST ORDERED' mod='priceandorder'}
</td></tr>
<tr><td class="line_po_center"><img src="{Configuration::get("priceandorder_piclink")}" alt="most ordered product's image"/>
      </td>
    </tr>
  </table>
</form>     
{if ($priceandorder->captcha=="Display:none") OR (Context::getContext()->customer->isLogged())}
<script>
  n = "{l s='ORDER NOW' mod='priceandorder'}";
  $("#recaptcha_submit_replace").replaceWith("<div align='center'><input type='submit' name='submit2' id='submit2' value='" + n + "' class='exclusive'/></div>");
</script>
{else}      
<script>
	document.getElementById("human").style.visibility='visible';
	
    var a = Math.ceil(Math.random() * 10);
    var b = Math.ceil(Math.random() * 10);       
    var c = a + b

	document.getElementById('a').innerHTML=a;	
	document.getElementById('b').innerHTML=b;
		
    function CaglarCodeCheck(){
        var d = document.getElementById('CaglarInput').value;
        if (d == c) {
			document.getElementById("human").style.visibility='hidden';
      n = "{l s='ORDER NOW' mod='priceandorder'}";
      $("#recaptcha_submit_replace").replaceWith("<div align='center'><input type='submit' name='submit2' id='submit2' value='" + n + "' class='exclusive'/></div>");
		}
		else
		{
			document.getElementById("submit2").style.visibility='hidden';
			document.getElementById("human").style.visibility='visible';
		}       
    }	
</script>
{/if}

<script type="text/javascript"> 
var frmvalidator = new Validator("form1");
frmvalidator.EnableMsgsTogether();

if(document.form1.email)
{
frmvalidator.addValidation("email","email","{l s='Please enter a valid e-mail address.' mod='priceandorder'}"); 
}
else 
{}
if(document.form1.product)
{
frmvalidator.addValidation("product","req","{l s='Please enter product description.' mod='priceandorder'}"); 
}
else 
{}
</script> 
</div>
</div>
