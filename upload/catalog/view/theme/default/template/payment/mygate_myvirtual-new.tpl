<form action="<?php echo $action; ?>" method="post" id="payment">
  <input type="hidden" name="Mode" value="<?php echo $test; ?>" />
  <input type="hidden" name="MerchantID" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="ApplicationID" value="<?php echo $application; ?>" />
  <input type="hidden" name="MerchantReference" value="ORD<?php echo $order_id; ?>" />
  <input type="hidden" name="Amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="Price" value="<?php echo $amount; ?>" />
  <input type="hidden" name="Currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="RedirectSuccessfulURL" value="<?php echo $callback; ?>" />
  <input type="hidden" name="RedirectFailedURL" value="<?php echo $callback; ?>" />
  <input type="hidden" name="ShippingCountryCode" value="<?php echo $country_code; ?>" />
  <input type="hidden" name="IPAddress" value="<?php echo $ip_address; ?>" />
  <?php if($products) { ?>
	<?php $i = 0; ?>
	<?php foreach($products AS $product) { ?>
		<?php $i++; ?>
		<input type="hidden" name="txtQty<?php echo $i; ?>" value="<?php echo $product['quantity']; ?>" />
		<input type="hidden" name="txtItemRef<?php echo $i; ?>" value="<?php echo $product['model']; ?>" />
		<input type="hidden" name="txtItemDescr<?php echo $i; ?>" value="<?php echo $product['name']; ?>" />
		<input type="hidden" name="txtItemAmount<?php echo $i; ?>" value="<?php echo $product['price']; ?>" />
	<?php } ?>
  <?php } ?>

  <input type="hidden" name="txtRecipient" value="<?php echo $name; ?>" />
  <?php for($i = 0, $count = count($address); $i < $count; $i++) { ?>
		<input type="hidden" name="txtShippingAddress<?php echo $i+1; ?>" value="<?php echo $address[$i]; ?>" />
  <?php } ?>
  <input type="hidden" name="txtShippingAddress<?php echo ++$i; ?>" value="<?php echo $country; ?>" />
  <input type="hidden" name="Variable1" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="Variable2" value="<?php echo amount; ?>" />
  <input type="hidden" name="txtOrderShippingCost" value="<?php echo $shipping_amount; ?>" />
  <input type="hidden" name="txtOrderDiscount" value="<?php echo $discount_amount; ?>" />

  <div class="buttons">
    <div class="right"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></div>
  </div>
</form>
