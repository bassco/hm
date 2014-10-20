<?php
/**
 * Mailchimp OpenCart Module, connects the MailChimp 
 * Newsletter services with your OpenCart store.
 *
 * @author    Ian Gallacher (www.opencartstore.com)
 * @version   1.5.3.x
 * @support   https://opencartstorecom.zendesk.com/home
 * @email   info@opencartstore.com
 */
?>

<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <a onclick="$('#save_without_close').val(1); $('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a>
        <a onclick="$('#form').submit();" class="button"><span><?php echo $button_save_close; ?></span></a>
        <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a>
      </div>
    </div>
    <div class="content">
      <div id="tabs" class="htabs">
        <a href="#tab-settings"><?php echo $text_settings; ?></a>
        <a href="#tab-sync"><?php echo $text_sync; ?></a>
        <a href="#tab-webhooks"><?php echo $text_webhooks; ?></a>
        <a href="#tab-help"><?php echo $text_help; ?></a>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab-settings">
        <table class="form">
          <tr>
            <td colspan="2"><b><?php echo $text_settings_about; ?></b>
          </tr>
          <tr>
            <td style="width: 300px!important">
              <span class="required">*</span><?php echo $entry_api_key; ?></td>
            <td>
              <input type="text" id="mailchimp_api_key" name="mailchimp_api_key" value="<?php echo $mailchimp_api_key; ?>" size="30" />
            </td>
          </tr>

          <tr>
            <td>
              <span class="required">*</span><?php echo $entry_list_id; ?></td>
            <td>
              <select name="mailchimp_list_id" id="mailchimp_list_id" style="width: 300px">

              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_double_optin; ?></td>
            <td>
              <select name="mailchimp_double_optin">
              <?php if ($mailchimp_double_optin) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr> 
          <tr>
            <td><?php echo $entry_update_existing ?></td>
            <td>
              <select name="mailchimp_update_existing">
              <?php if ($mailchimp_update_existing) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_extra_fields; ?></td>
            <td>
              <select name="mailchimp_extra_fields">
              <?php if ($mailchimp_extra_fields) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_email_type; ?></td>
            <td>
              <select name="mailchimp_email_type">
              <?php if ($mailchimp_email_type) { ?>
                <option value="1" selected="selected"><?php echo $text_html; ?></option>
                <option value="0"><?php echo $text_plain_text; ?></option>
              <?php } else { ?>
                <option value="1"><?php echo $text_html; ?></option>
                <option value="0" selected="selected"><?php echo $text_plain_text; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr>
        </table>

        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_layout; ?></td>
              <td class="left"><?php echo $entry_position; ?></td>
              <td class="left"><?php echo $entry_status; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $module_row = 0; ?>
          <?php foreach ($modules as $module) { ?>
            <tbody id="module-row<?php echo $module_row; ?>">
              <tr>
                <td class="left">
                  <select name="mailchimp_module[<?php echo $module_row; ?>][layout_id]">
                  <?php foreach ($layouts as $layout) { ?>
                  <?php if ($layout['layout_id'] == $module['layout_id']) { ?>
                  <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
              </select>
            </td>
            <td class="left">
              <select name="mailchimp_module[<?php echo $module_row; ?>][position]">
                <?php if ($module['position'] == 'content_top') { ?>
                <option value="content_top" selected="selected"><?php echo $text_content_top; ?></option>
                <?php } else { ?>
                <option value="content_top"><?php echo $text_content_top; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'content_bottom') { ?>
                <option value="content_bottom" selected="selected"><?php echo $text_content_bottom; ?></option>
                <?php } else { ?>
                <option value="content_bottom"><?php echo $text_content_bottom; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_left') { ?>
                <option value="column_left" selected="selected"><?php echo $text_column_left; ?></option>
                <?php } else { ?>
                <option value="column_left"><?php echo $text_column_left; ?></option>
                <?php } ?>
                <?php if ($module['position'] == 'column_right') { ?>
                <option value="column_right" selected="selected"><?php echo $text_column_right; ?></option>
                <?php } else { ?>
                <option value="column_right"><?php echo $text_column_right; ?></option>
                <?php } ?>
              </select>
            </td>
            <td class="left">
              <select name="mailchimp_module[<?php echo $module_row; ?>][status]">
                <?php if ($module['status']) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </td>
            <td class="right">
              <input type="text" name="mailchimp_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" />
            </td>
            <td class="left">
              <a onclick="$('#module-row<?php echo $module_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a>
            </td>
          </tr>
        </tbody>
        <?php $module_row++; ?>
        <?php } ?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="left"><a onclick="addModule();" class="button"><span><?php echo $button_add_module; ?></span></a></td>
          </tr>
        </tfoot>
        </table>
      </div>
    
      <div id="tab-sync">
        <table class="form">
          <tr>
            <td colspan="2"><b><?php echo $text_sync_about; ?></b>
          </tr>
          <tr>
            <td style="width: 300px!important">
              <span class="required">*</span><?php echo $entry_list_id; ?>
              <span class="help">Choose the MailChimp list you would like to sync with. This list will automatically populate when you have entered a valid API Key.</span>
            </td>
            <td>
              <select name="sync_list_id" id="sync_list_id" style="width: 300px">
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_sync_customer_group; ?>
              <span class="help">Choose the OpenCart customer group to sync. Default is all customers.</span>
            </td>
            <td>
              <select name="sync_customer_group" id="sync_customer_group" style="width: 300px">
              <option value="ALL">All Customer Groups</option>
              <?php foreach ($sync_customer_groups as $group) { ?>
                <option value="<?php echo $group['customer_group_id']; ?>"><?php echo $group['name']; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr>
          <tr>
            <td>
              <?php echo $entry_sync_all; ?>
              <span class="help">Warning! Be careful syncing any customers who have not asked to receive emails. Ticking this box will subscribe your complete customer list, even those that have opted out.</span>
            </td>
            <td>
              <input type="checkbox" name="sync_all" id="sync_all" onclick="alert('<?php echo $text_mailchimp_sync_alert; ?>')"</p>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <a href="#" class="button" id="mailchimp_sync"><span><?php echo $button_start_sync; ?></span></a>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <div id="mailchimp_sync_status"></div>
            </td>
          </tr> 
        </table>
      </div>
    
      <div id="tab-webhooks">
        <table class="form">
          <tr>
            <td colspan="2"><b><?php echo $text_webhooks_about; ?></b>
          </tr>
          <tr>
            <td style="width: 300px!important"><?php echo $entry_webhooks; ?>
            <span class="help">This will allow MailChimp to send mailing list subscribe and unsubscribe information back to OpenCart where it will be updated.
            </td>
            <td>
              <select name="mailchimp_webhooks">
              <?php if ($mailchimp_webhooks) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
              </select>
            </td>
          </tr> 
        </table>
      </div>

      <div id="tab-help">
        <table class="form">
          <tr>
            <td style="vertical-align: middle;"><?php echo $text_mailchimp_support; ?></td>
	          <td style="vertical-align: middle;"><a href="http://www.mailchimp.com/resources" target="_blank">http://www.mailchimp.com/resources</a></td>
          </tr> 
          <tr>
            <td style="vertical-align: middle;"><?php echo $text_version_status; ?></td>
	          <td style="vertical-align: middle;"><?php echo $text_version_number; ?></td>
          </tr>
          <tr>
            <td valign="top"><?php echo $text_author; ?></td>
            <td>
 	          <a href="mailto:info@opencartstore.com">info@opencartstore.com</a><br /><br />
	          <a href="https://opencartstorecom.zendesk.com/" target="_blank">https://opencartstorecom.zendesk.com/</a><br />
	        </td>
          </tr>
        </table> 
      </div>
      <input type="hidden" id="save_without_close" name="save_without_close" value="0">
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('#tabs a').tabs();

var module_row = <?php echo $module_row; ?>;

function addModule() {	
	html  = '<tbody id="module-row' + module_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><select name="mailchimp_module[' + module_row + '][layout_id]">';
	<?php foreach ($layouts as $layout) { ?>
	html += '      <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>';
	<?php } ?>
	html += '    </select></td>';
	html += '    <td class="left"><select name="mailchimp_module[' + module_row + '][position]">';
	html += '      <option value="content_top"><?php echo $text_content_top; ?></option>';
	html += '      <option value="content_bottom"><?php echo $text_content_bottom; ?></option>';
	html += '      <option value="column_left"><?php echo $text_column_left; ?></option>';
	html += '      <option value="column_right"><?php echo $text_column_right; ?></option>';
	html += '    </select></td>';
	html += '    <td class="left"><select name="mailchimp_module[' + module_row + '][status]">';
    html += '      <option value="1" selected="selected"><?php echo $text_enabled; ?></option>';
    html += '      <option value="0"><?php echo $text_disabled; ?></option>';
    html += '    </select></td>';
	html += '    <td class="right"><input type="text" name="mailchimp_module[' + module_row + '][sort_order]" value="" size="3" /></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#module tfoot').before(html);
	
	module_row++;
}

//--></script> 

<script type="text/javascript"><!--
function display_mailchimp_lists() 
{
    var mailchimp_list_id;

    $.ajax({
        type:     'post',
        url:      'index.php?route=module/mailchimp/ajax_get_selected_list&token=<?php echo $this->session->data['token']; ?>',  
        dataType: 'json',
    success:  function(response) 
    {
        mailchimp_list_id = response;
    }    
    });

    // Get all the available lists.
    $.ajax({
        type:     'post',
        url:      'index.php?route=module/mailchimp/ajax_get_available_lists&token=<?php echo $this->session->data['token']; ?>',
        data:     {'mailchimp_api_key' : $('#mailchimp_api_key').val()},
    dataType: 'json',
    success:  function(response) 
    {
        if (response) {
            var html = [];

            $.each(response, function (key, value) 
            {
                html += '<option value="' + value.id + '">' + value.name + ' (' + value.member_count + ')' + '</option>';
            }
            );
          
            $('#mailchimp_list_id, #sync_list_id').empty().html(html);

            $.each($('#mailchimp_list_id').children(), function(index) 
            {
                if ($(this).val() == mailchimp_list_id) {
                    $(this).attr('selected', 'selected');
                }
            }
            );
        }
    }         
    });
    };
    
$(function () 
{
    $('#mailchimp_api_key').bind('change keyup blur', display_mailchimp_lists) // Bind in case value get changed

    if ($('#mailchimp_api_key').val() != '') {
        display_mailchimp_lists();
    }
});

//--></script> 

<script type="text/javascript"><!--
var mailchimp_status = {
  dialog: function() {
    $('#mailchimp_sync_status').dialog({ 
      "modal" : true,
      "title" : "Status",
      "position" : "center",
      "width" : 500,
      "minHeight" : 300
    });  
  },
	update: function(msg) {
      $('#mailchimp_sync_status').append(msg + '<br>');
	},
    warning: function(msg) {
      $('#mailchimp_sync_status').append('<span style="color: red">' + msg + '</span><br>');   
    },
	clear: function() {
      $('#mailchimp_sync_status').empty();
	}
}

$('#mailchimp_sync').click(function(e){
  e.preventDefault();
  mailchimp_status.dialog(); // Open the feedback dialog window
    
  var sync_all = "false";

  if ($('#sync_all').is(':checked')) {
    var sync_all = "true";
  } 

  if (check_required()) {		
	  $.ajax({
      type:      'post',
			url:       'index.php?route=module/mailchimp/ajax_get_subscribers&token=<?php echo $this->session->data['token']; ?>',
      data:      {"sync_all" : sync_all, "sync_customer_group" : $('#sync_customer_group').val()},
			dataType:  'text',
			success:   function(response) {
        if (response) {
          mailchimp_status.update('Sync All: ' + sync_all);
					mailchimp_status.update('Found ' + response + ' subscribers.');
					mailchimp_status.update('Sending data to MailChimp.');		
					
					$.ajax({
						type:     'post',
						url:      'index.php?route=module/mailchimp/ajax_get_subscribers&token=<?php echo $this->session->data['token']; ?>',
						data:     {"update" : "true", 
                       "sync_all" : sync_all, 
                       "mailchimp_list_id" : $('#sync_list_id option:selected').val(), 
                       "mailchimp_api_key" : $('#mailchimp_api_key').val(), 
                       "sync_customer_group" : $('#sync_customer_group').val()},
						dataType: 'json',
				  	success:  function (response) {
							if (response.error) {
                mailchimp_status.update('Error sending to MailChimp.');
                mailchimp_status.update('Error:   ' + response.msg);
              } else {
								mailchimp_status.update('Data sent to MailChimp.');
								mailchimp_status.update('Number of subscribers added:   ' + response.add_count);
								mailchimp_status.update('Number of subscribers updated:   ' + response.update_count);
								mailchimp_status.warning('Number of unsuccesfully subscribed:   ' + response.error_count);
							}
                            	
              if (parseInt(response.error_count) > 0) {
								mailchimp_status.warning('Listing All Errors...');
								var i;
									
                for (i in response.errors) {
						  		mailchimp_status.warning(response.errors[i].email);
									mailchimp_status.warning(response.errors[i].message);
								}
							}
						} 
					});
				} else {
					mailchimp_status.warning('Error: There are no subscribers in the database.');
				}
			}
		});
    }
    display_mailchimp_lists();
});

/**
 * Check that the API key and list ID are set.
 * 
 * @access public
 * @return boolean
 */
function check_required () {
	mailchimp_status.clear();
	mailchimp_status.update('Initializing...');

	if ($('#mailchimp_api_key').val() == '') {
		mailchimp_status.warning('Checking for MailChimp API Key: failed.');
		return false;
	} else {
		mailchimp_status.update('Checking for MailChimp API Key: ' + $('#mailchimp_api_key').val());
	}
	
	if ($('#sync_list_id').val() ==  null) {
		mailchimp_status.warning('Checking for Mailchimp List ID: failed.');
		return false;
	} else {
		mailchimp_status.update('Checking for Mailchimp List ID: ' + $('#sync_list_id').val());
	}

	return true;
}
//--></script>