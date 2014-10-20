<?php
/**
 * Mailchimp OpenCart Module, connects the MailChimp 
 * Newsletter services with your OpenCart store.
 *
 * @author		Ian Gallacher (www.opencartstore.com)
 * @version		1.5.3.x
 * @support		https://opencartstorecom.zendesk.com/home
 * @email		info@opencartstore.com
 */
?>
<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <form id="mailchimp">
	  <div>
	    <label><span class="required">*</span> <?php echo $entry_email; ?></label>
	    <input type="text" name="email" id="email"/>
	    <?php if (isset($extra_fields)) { ?>
	      <label><span class="required">* </span><?php echo $entry_firstname; ?></label>
	      <input type="text" name="firstname" id="firstname"/>
	      <label><span class="required">* </span><?php echo $entry_lastname; ?></label>
	      <input type="text" name="lastname" id="lastname"/>
	    <?php } ?>
	  </div>
      <div id="mailchimpStatus"></div>
	  <div style="margin-top: 5px"><a class="button" id="mailchimpButton"><span><?php echo $button_submit; ?></span></a></div>
	</form>
  </div>
</div>

<script type="text/javascript"><!--
$(function() 
{
	$('#mailchimpButton').live('click', function () {
		$.ajax({
			url: 		'index.php?route=module/mailchimp/ajax_send_subscribe',
			type: 		'post',
			dataType: 	'json',
			data: 		{"email" : $('#email').val(), 
						 "firstname" : $('#firstname').val(), 
						 "lastname" : $('#lastname').val()},
			beforeSend: function() 
			{
				$('#mailchimpStatus').html('<img src="image/ajax-loader.gif">');
				
				if ($('#email').val() == '' || $('#firstname').val() == '' || $('#lastname').val() == '') {
					$('#mailchimpStatus').html('<?php echo $text_missing_field; ?>');
					mailchimpFailure();
					return false;
				}
			},
			success: function(response)
			{
				$('#mailchimpStatus').html(response.message);
					
				if (response.status) {
					mailchimpSuccess();
				} else {
					mailchimpFailure();
				}
			}
		});
	});
	
	// Add some CSS to the elements.
	$('#mailchimp input').css({
		'display':			'block',
		'margin-bottom':	'10px'
	});
	
	function mailchimpSuccess() 
	{
		$('#mailchimpStatus').css({
			'margin':		'3px 0px 3px 0px',
			'padding':		'3px',
			'background':	'#acff99',
			'border':		'1px solid #53ba3b',
			'color':		'#53ba3b'
		});
	}

	function mailchimpFailure() 
	{
		$('#mailchimpStatus').css({
			'margin':		'3px 0px 3px 0px',
			'padding':		'3px',
			'background':	'#ff9d9d',
			'border':		'1px solid #fb3f3f',
			'color':		'#fb3f3f'
		});
	}
});
//--></script>