{*
*	Module Name: Price and Order - Column Quote Form
*	Module URI: Please contact with info@megventure.com
*	Description: A Price Quoting Module for Demanded Orders
*	Version: 1.9.4
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

<div class="block">
<h4 class="title_block">{l s='GET YOUR QUOTE' mod='priceandorder'}</h4>
<div class="block_content" style="padding:0px;"> 
<script>
function clearText(thefield){
if (thefield.defaultValue==thefield.value)
thefield.value = ""
} 
</script>
{if isset($confirmation1)}<div style="background:#3F6; padding:5px; border:thin; border-color:#030; text-align:center">{l s='Your quote request has been successfully sent to our team.' mod='priceandorder'}</div><br />{else}{/if}
<form action="{$request_uri|escape:'htmlall':'UTF-8'}" method="post" name="form1" id="form1" class="form1">

  <table border="0" cellspacing="5" cellpadding="6">
  {if Context::getContext()->customer->isLogged()}
    <tr>
      <td class="line_bq">
		{$cookie->customer_firstname} {$cookie->customer_lastname},<br />{l s='If you want to get the best price for the product you looking for, please fill in the empty box below.' mod='priceandorder'}
        </td>
    </tr>
	{else}
    <tr>
      <td class="line_bq">    
        {l s='Dear Visitor' mod='priceandorder'}, <br />{l s='If you want to get the best price for the product you looking for, please fill in the boxes below.' mod='priceandorder'}
        </td>
    </tr>    
    {/if}

    <tr>
      <td class="line_bq_center">
        <LABEL for="product">{l s='Please write product`s name and model or website link' mod='priceandorder'}</LABEL>
        <textarea style="width: 90%;" name="product" cols="20"  rows="5" id="product"></textarea><sup> *</sup>
      </td>
    </tr>

{if ($priceandorder->customername=="Display:none") AND ($priceandorder->contactaddress=="Display:none") AND ($priceandorder->contacttown=="Display:none") AND ($priceandorder->phone=="Display:none") AND ($priceandorder->contactemail=="Display:none")}
{else}
<tr>
<td colspan="2">
<hr />
<h5>{l s='Contact Information' mod='priceandorder'}</h5>
</td>
</tr> 
{/if}   

{if Context::getContext()->customer->isLogged()}     
{if $priceandorder->customername=="Display:none"}
<input name="name_cust" type="hidden" value="{$cookie->customer_firstname} {$cookie->customer_lastname}"  size ="23" maxlength="70" />
{else}
<tr>
<td class="line_bq_center" colspan="2">
	<LABEL for="name_cust">{l s='Full name' mod='priceandorder'}</LABEL><sup> *</sup>
    <input name="name_cust" type="text" value="{$cookie->customer_firstname} {$cookie->customer_lastname}" onFocus="clearText(this)"  size ="23" maxlength="70" />
</p>
</td>
</tr> 
{/if}
{else}
{if $priceandorder->customername=="Display:none"}
<input name="name_cust" type="hidden" value="{l s='Not entered' mod='priceandorder'}"  size ="23" maxlength="70" />
{else}
<tr>
<td class="line_bq_center" colspan="2">      
	<LABEL for="name_cust">{l s='Full name' mod='priceandorder'}</LABEL><sup> *</sup>
	<input name="name_cust" type="text" value="" size ="23" maxlength="70" />    
</td>
</tr>        
{/if}
{/if}

{if $priceandorder->contactaddress=="Display:none"}
{else}
<tr>
<td class="line_bq_center" colspan="2">
	<LABEL for="priceandorder_contactaddress">{l s='Address' mod='priceandorder'}</LABEL>
    <input name="priceandorder_contactaddress" type="text" value="" size ="23" maxlength="255" />
</td>
</tr> 
{/if}     

{if $priceandorder->contacttown=="Display:none"}
{else}
<tr>
<td class="line_bq_center" colspan="2">
	<LABEL for="priceandorder_contacttown">{l s='Town (City)' mod='priceandorder'}</LABEL>
    <input name="priceandorder_contacttown" type="text" value="" size ="23" maxlength="255" />
</td>
</tr> 
{/if}

{if $priceandorder->phone=="Display:none"}
{else}
<tr>
<td class="line_bq_center" colspan="2">
	<LABEL for="phone">{l s='Phone number' mod='priceandorder'}</LABEL>
    <input name="phone" type="text" value="" size ="23" maxlength="70" />   
</td>
</tr>        
{/if}

{if Context::getContext()->customer->isLogged()}
	{if $priceandorder->contactemail=="Display:none"}
		<input name="email" type="hidden" value="{$cookie->email}" size ="23" maxlength="70" />
	{else}
		<tr>
		<td class="line_bq_center" colspan="2">
			<LABEL for="email">{l s='E-mail Address' mod='priceandorder'}</LABEL><sup> *</sup>	
    		<input name="email" type="text" value="{$cookie->email}"  onFocus="clearText(this)" size ="23" maxlength="70" />
		</td>
		</tr> 
	{/if}
{else}
	<tr>
	<td class="line_bq_center" colspan="2">         
		<LABEL for="email">{l s='E-mail Address' mod='priceandorder'}</LABEL><sup> *</sup>
		<input name="email" type="text" value="{l s='Required for Contact' mod='priceandorder'}" onFocus="clearText(this)" size ="23" maxlength="70" />
	</td>
	</tr>
{/if}

{if ($priceandorder->quantity=="Display:none") AND ($priceandorder->destination=="Display:none") AND ($priceandorder->urgency=="Display:none") AND ($priceandorder->paypal=="Display:none") AND ($priceandorder->first_order=="Display:none")}
{else}
<tr>
<td colspan="2">
<hr />
<h5>{l s='Other Details' mod='priceandorder'}</h5>
</td>
</tr> 
{/if}   

{if $priceandorder->quantity=="Display:none"}
{else}
<tr>
<td class="line_bq_center" colspan="2">
	<LABEL for="shipping_quantity">{l s='Shipping quantity' mod='priceandorder'}</LABEL>
    <input name="shipping_quantity" type="text" id="shipping_quantity" value="" size ="23" maxlength="70" />
</td>
</tr>        
{/if}     
 
{if $priceandorder->destination=="Display:none"}
{else}
<tr>
<td class="line_bq_center" colspan="2">
	<LABEL for="shipping_destination">{l s='Shipping destination' mod='priceandorder'}</LABEL>
    <input name="shipping_destination" type="text" id="shipping_destination" value="" size ="23" maxlength="70" />
</td>
</tr>   
{/if} 
    
{if $priceandorder->urgency=="Display:none"}
{else}
<tr>    
<td class="line_bq_center" align="center">
	<LABEL for="urg1">{l s=' Urgent' mod='priceandorder'}</LABEL>
    <input type="radio" name="urgency" id="urg1" value="{l s='- Urgent response needed' mod='priceandorder'}" checked="checked"/>

	<LABEL for="urg2">{l s=' Not Urgent' mod='priceandorder'}</LABEL>
	<input type="radio" name="urgency" id="urg2" value="{l s='- Urgent response is not needed' mod='priceandorder'}"/><br>
	<br>
</td>
</tr>   
{/if}

{if $priceandorder->paypal=="Display:none"}
&nbsp;
{else}
<tr>    
<td class="line_bq_center1" colspan="2">
<input style="width:20px;" name="paypal" type="checkbox" value="{l s='- Customer has a PayPal account' mod='priceandorder'}" /><div style="float:left;line-height: 12px;">{l s=' I have PayPal account' mod='priceandorder'}</div>
</td>
</tr>
{/if}

{if $priceandorder->first_order=="Display:none"}
&nbsp;
{else}
<tr>
<td class="line_bq_center">
<input style="width:20px;" name="first_order" type="checkbox" value="{l s='- This will be the first order of the customer' mod='priceandorder'}" /><div style="float:left;line-height: 12px;">{l s=' This is my first order' mod='priceandorder'}</div>              
<br>
</td>
</tr>  
<tr><td style="padding-top:10px;padding-bottom:10px;"><sup>* </sup>{l s='denotes required fields' mod='priceandorder'} </td></tr>
 
{/if}
          
    <tr>
      <td>
<!--Invisible Data Transfer Section-->      
<input name="language" type="hidden" id="language" value="{(!isset($cookie) OR !is_object($cookie)) ? (int)(Configuration::get('PS_LANG_DEFAULT')) : (int)($cookie->id_lang)}"/>
<input name="homedir" type="hidden" id="homedir" value="{$base_dir}"/> 
<input name="recipient_address" type="hidden" id="recipient_address" value="{$priceandorder->recmail|stripslashes}"/>
<input name="shop_logo" type="hidden" id="shop_logo" value="{$img_ps_dir}logo.jpg"/>
<!--Invisible Data Transfer Section--> 

{if ($priceandorder->captcha=="Display:none") OR (Context::getContext()->customer->isLogged())}
<input id='CaglarInputPO' name='CaglarInputPO' type='hidden' value='10'/>
{else}             
<!--CAPTCHA Security Section-->
<div align="center" style="{$priceandorder->captcha}">
<div style="float:left;padding:0px 2px;">{l s='What is' mod='priceandorder'}</div><div id="a" style="float:left;padding:0px 2px;"></div><div style="float:left;padding:0px 2px;">+</div><div id="b" style="float:left;padding:0px 2px;clear:right;"></div><div style="float:left;padding:0px 2px;">?</div>
<input id='CaglarInputPO' name='CaglarInputPO' type='text' maxlength='2' size='2' style="width: 30px;float: left;clear: none;margin-top: -6px;height: 26px;text-align: center;"/>
<input id="Button1" type="button" value="{l s='OK' mod='priceandorder'}" onclick="CaglarCodeCheck()" style="width: 16%;float: left;clear: none;height: 26px;margin-top: -6px;"/>
</div>
<div id="human" style="text-align:center;width:90%;color:#F00;font-weight:bold;clear:both;">{l s='Proove that you are human to see the Submit button' mod='priceandorder'}</div>
<!--CAPTCHA Security Section-->
{/if} 

<!--Submit Button-->
   <input type="hidden" id="recaptcha_submit_replace">
<!--Submit Button-->          
<!--Reset Button-->          
          <p align="center" style="padding-top: 20px;">
            <input type="reset" name="clear" id="clear" value="{l s='Reset Form' mod='priceandorder'}" class="button pricebutton btn btn-secondary" />
          </p>
<!--Reset Button-->
          <div>
            <label for="gdpr1" style="font-size:x-small;padding-top: 10px; text-align: left; width: 100%;">
              <input style="width:7%;" data-toggle="switch" class="tiny" id="gdpr1" data-inverse="true" type="checkbox" name="gdpr1" value="true" checked> 
              {l s='by continuing, you agree our ' mod='priceandorder'} <a href="{$priceandorder->terms_conditions_link}" target="_blank">{l s='Terms & Conditions' mod='priceandorder'}</a>&nbsp;{l s='and' mod='priceandorder'}&nbsp;<a href="{$priceandorder->privacy_policy_link}" target="_blank">{l s='Privacy Policy' mod='priceandorder'}</a><sup> *</sup>
            </label>
          </div>        
      </td>
    </tr>
  </table>
</form>   

<script type="text/javascript"> 
var frmvalidator = new Validator("form1");
frmvalidator.EnableMsgsTogether();
frmvalidator.addValidation("product","req","{l s='Requested product description is required.' mod='priceandorder'}"); 
frmvalidator.addValidation("name_cust","req","{l s='Contact name is required.' mod='priceandorder'}"); 
frmvalidator.addValidation("email","email","{l s='Please enter a valid e-mail address.' mod='priceandorder'}"); 
frmvalidator.addValidation("gdpr1","shouldselchk","{l s='You must accept the GDPR notice to proceed submission.' mod='priceandorder'}"); 
</script>

{if ($priceandorder->captcha=="Display:none") OR (Context::getContext()->customer->isLogged())}
<script>
    n = "{l s='ORDER NOW' mod='priceandorder'}";
    $("#recaptcha_submit_replace").replaceWith("<p align='center'><input type='submit' name='submit1' id='submit1' value='" + n + "' class='button pricebutton btn btn-primary'/></p>");
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
        var d = document.getElementById('CaglarInputPO').value;
        if (d == c) {
			document.getElementById("human").style.visibility='hidden';
      n = "{l s='ORDER NOW' mod='priceandorder'}";
      $("#recaptcha_submit_replace").replaceWith("<p align='center'><input type='submit' name='submit1' id='submit1' value='" + n + "' class='button pricebutton btn btn-primary'/></p>");
		}
		else
		{
			document.getElementById("human").style.visibility='visible';
      $("#submit1").replaceWith("<input type='hidden' id='recaptcha_submit_replace'/>");
		}       
    }	
</script>
{/if}
           <p align="center"><a href="{$priceandorder->more_info_link|stripslashes}">{l s='For More Information please click here' mod='priceandorder'}</a></p>
          <p align="center">{l s=' MOST ORDERED' mod='priceandorder'}</p>
          <p align="center"><img src="{$module_dir}{$priceandorder->most_ordered|stripslashes}" alt="most ordered product's image"/></p>
</div>
</div>
