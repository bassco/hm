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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a><a onclick="$('form').submit();" class="button"><span><?php echo $button_delete; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
    <table class="list">
      <thead>
        <tr>
          <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
	  <td class="left"><?php echo $column_order_ref; ?></td>
	  <td class="left"><?php echo $column_supplier; ?></td>
	  <td class="left"><?php echo $column_status; ?></td>
          <td class="right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
	  <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_order_ref" value="<?php echo $filter_order_ref; ?>" /></td>
            <td><select name="filter_supplier">
		    <option value="*">&nbsp;</option>
		    <?php if ($suppliers) { ?>
		    <?php foreach($suppliers as $supplier) { ?>
		    <?php if ($supplier['id'] == $filter_supplier) { ?>
		    <option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
		    <?php } else { ?>
		    <option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
		    <?php } ?>
		    <?php } ?>
		    <?php } ?>
		</select></td>
            <td><select name="filter_status">
		    <option value="*">&nbsp;</option>
		    <?php foreach($statuses as $status) { ?>
		    <?php if ($status['id'] == $filter_status) { ?>
		    <option value="<?php echo $status['id']; ?>" selected="selected"><?php echo $status['name']; ?></option>
		    <?php } else { ?>
		    <option value="<?php echo $status['id']; ?>"><?php echo $status['name']; ?></option>
		    <?php } ?>
		    <?php } ?>
		</select></td>
            <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
          </tr>
        <?php if ($purchase_orders) { ?>
        <?php foreach ($purchase_orders as $purchase_order) { ?>
        <tr>
          <td style="text-align: center;"><?php if ($purchase_order['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $purchase_order['purchase_order_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $purchase_order['purchase_order_id']; ?>" />
              <?php } ?></td>
	  <td class="left"><?php echo $purchase_order['order_ref']; ?></td>
	  <td class="left"><?php echo $purchase_order['supplier_name']; ?></td>
	  <td class="left"><?php echo $purchase_order['status_text']; ?></td>
          <td class="right"><?php foreach ($purchase_order['action'] as $action) { ?>
            [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/purchase_order&token=<?php echo $token; ?>';

	var filter_order_ref = $('input[name=\'filter_order_ref\']').attr('value');

	if (filter_order_ref) {
		url += '&filter_order_ref=' + encodeURIComponent(filter_order_ref);
	}

	var filter_supplier = $('select[name=\'filter_supplier\']').val();

	if (filter_supplier != '*') {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
}
//--></script>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<?php echo $footer; ?>
