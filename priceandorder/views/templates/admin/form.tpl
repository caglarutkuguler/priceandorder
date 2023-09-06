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

	<form name="configuration_form" action="{$request_uri}" method="post">
			<font size="2">{l s='Thanks for using Price & Order Module designed by' mod='priceandorder'} <a href="https://addons.prestashop.com/en/contact-form.php">MEG Venture</a>. {l s='All rights are reserved. Please fill out all the fields below to function the module properly.' mod='priceandorder'}</font>
      <br><br><hr><br>
			<fieldset>
			<legend><img src="{$path}img/admin/prefs.gif" />{l s='Configuration' mod='priceandorder'}</legend>
			<label>{l s='Shipping Destination Option' mod='priceandorder'}</label>
			<img src="{$path}img/admin/enabled.gif" /><input type="radio" name="destination_settings" value="display:block" {if ($destination == "display:block")} checked="checked" {/if}/>{l s='On' mod='priceandorder'}&nbsp;&nbsp;<img src="{$path}img/admin/disabled.gif" /><input type="radio" name="destination_settings" value="display:none"  {if ($destination == "display:block")} checked="checked" {/if}/>{l s='Off' mod='priceandorder'}
			<div class="margin-form">{l s='Asks for shipping destination if it enabled.' mod='priceandorder'}</div>

			<label>{l s='Order Urgency Option' mod='priceandorder'}</label>
			<img src="{$path}img/admin/enabled.gif" /><input type="radio" name="urgency_settings" value="display:block" {if ($urgency == "display:block")} checked="checked" {/if}/>{l s='On' mod='priceandorder'}&nbsp;&nbsp;<img src="{$path}img/admin/disabled.gif" /><input type="radio" name="urgency_settings" value="display:none"  {if ($urgency == "display:block")} checked="checked" {/if}/>{l s='Off' mod='priceandorder'}
			<div class="margin-form">{l s='Asks for the urgency status of the customer order if it is enabled.' mod='priceandorder'}</div>

			<label>{l s='Paypal Account Question Option' mod='priceandorder'}</label>
			<img src="{$path}img/admin/enabled.gif" /><input type="radio" name="paypal_settings" value="display:block" {if ($paypal == "display:block")} checked="checked" {/if}/>{l s='On' mod='priceandorder'}&nbsp;&nbsp;<img src="{$path}img/admin/disabled.gif" /><input type="radio" name="paypal_settings" value="display:none"  {if ($paypal == "display:block")} checked="checked" {/if}/>{l s='Off' mod='priceandorder'}
			<div class="margin-form">{l s='Asks customer if she/he has a Paypal account if it is enabled.' mod='priceandorder'}</div>

			<label>{l s='First Order Question Option' mod='priceandorder'}</label>
			<img src="{$path}img/admin/enabled.gif" /><input type="radio" name="first_order_settings" value="display:block" {if ($first_order == "display:block")} checked="checked" {/if}/>{l s='On' mod='priceandorder'}&nbsp;&nbsp;<img src="{$path}img/admin/disabled.gif" /><input type="radio" name="first_order_settings" value="display:none"  {if ($first_order == "display:block")} checked="checked" {/if}/>{l s='Off' mod='priceandorder'}
			<div class="margin-form">{l s='Asks customer whether it is his/her first order if it is enabled.' mod='priceandorder'}</div>

			<label>{l s='Quote Recipient E-mail' mod='priceandorder'}</label>
			<div class="margin-form">
				<input type="text" name="recipient_mail" value="{$recmail}"/>{l s='This is the e-mail address that will be notified about the quote requests. Ex: yourname@domain.com' mod='priceandorder'}
			</div>
			<label>{l s='More Info Link' mod='priceandorder'}</label>
			<div class="margin-form">
				<input type="text" name="link" value="{$infolink}"/>Ex: https://www.prestashop.com'
			</div>
			<label>{l s='Most Ordered Product Picture Link' mod='priceandorder'}</label>
			<div class="margin-form">
				<input type="text" name="picture_link" value="{$piclink}"/>{l s='Picture size should be 144x98 px. Ex:/modules/priceandorder/img/ms.png' mod='priceandorder'}
			</div><br>
			<label>{l s='CAPTCHA Security' mod='priceandorder'}</label>
			<div class="margin-form">
			<img src="{$path}img/admin/enabled.gif" /><input type="radio" name="captcha_settings" value="display:block"{if ($captcha == "display:block")} checked="checked" {/if}/>{l s='On' mod='priceandorder'}&nbsp;&nbsp;<img src="{$path}img/admin/disabled.gif" /><input type="radio" name="captcha_settings" value="display:none"  {if ($captcha == "display:block")} checked="checked" {/if}/>{l s='Off' mod='priceandorder'}</div>
			<div class="margin-form">{l s='CAPTCHA prevents robot spam mails, however bothers visitors.' mod='priceandorder'}</div>
			</fieldset><br>
			<input type="submit" name="submit" value="{l s='Update' mod='priceandorder'}" class="button" />
			<br><hr><img border="0" src="{$path}img/Help.png" width="16" height="16">{l s='Please contact for further suggestions, feedback, update and support at' mod='priceandorder'} <a href="https://addons.prestashop.com/en/contact-form.php">{l s='MODULE SUPPORT' mod='priceandorder'}</a>
	</form>
