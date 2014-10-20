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

// Heading
$_['heading_title']    			= 'MailChimp';

// Text
$_['text_settings_about']     	= 'Basic MailChimp Settings';
$_['text_sync_about']     		= 'Synchronise OpenCart with MailChimp';
$_['text_webhooks_about']     	= 'Enable MailChimp Webhooks Support';
$_['text_module']    			= 'Module';
$_['text_success']     			= 'Success: You have modified MailChimp settings!';
$_['text_content_top']    		= 'Content Top';
$_['text_content_bottom']		= 'Content Bottom';
$_['text_column_left']    		= 'Column Left';
$_['text_column_right']   		= 'Column Right';
$_['text_settings']				= 'Settings';
$_['text_sync']					= 'Sync';
$_['text_webhooks']				= 'Webhooks';
$_['text_help']					= 'Help';
$_['text_version_status']  		= 'Version Status';
$_['text_version_number']  		= '1.5.3.x';
$_['text_author']	     		= 'Author Details';
$_['text_mailchimp_support'] 	= 'Mailchimp Support Site';
$_['text_mailchimp_sync_alert']	= 'WARNING WARNING WARNING! It is illegal to send unsolicited email. Only choose this option if your customers wish to receive emails from you and you have ' .	
		  					      'proof to verify this. \n\nThere are heavy penalties for sending SPAM. You may get banned from MailChimp or charged under the various anti spam laws. Use ' .							    
		  					      'at your own risk.';
$_['text_html']					= 'Html';
$_['text_plain_text']			= 'Plain text';

// Entry
$_['entry_api_key']				= 'MailChimp API Key:<span class="help">You can find your API key by logging into your MailChimp account and going to Account -> API Keys & Authorized Apps.</span>';
$_['entry_list_id']				= 'MailChimp List:<span class="help">This list will automatically populate when you have entered a valid API Key.</span>';
$_['entry_double_optin']		= 'Double Opt-in:<span class="help">Requires that someone sign up, and then click a link to confirm that they want to be subscribed to your list before actually being added.</span>';
$_['entry_update_existing']		= 'Update Existing:<span class="help">Whether existing subscribers should be updated instead of throwing an error.</span>';
$_['entry_extra_fields']		= 'Extra Customer Fields:<span class="help">Adds extra fields to the front-end module to capture last names & first names.</span>';
$_['entry_email_type']			= 'Email Type:<span class="help">Select default email type for front end subscribers.</span>';
$_['entry_webhooks']			= 'Webhooks:';
$_['entry_status']     			= 'Status:';
$_['entry_layout']        		= 'Layout:';
$_['entry_position']     		= 'Position:';
$_['entry_sort_order']     		= 'Sort Order:';
$_['entry_sync_customer_group']	= 'Customer Group:';
$_['entry_sync_all']     		= 'Sync All Customers:';

// Buttons
$_['button_save_close']			= 'Save & Close';
$_['button_start_sync']			= 'Start Sync';

// Error
$_['error_permission'] 			= 'Warning: You do not have permission to modify MailChimp module!';
?>
