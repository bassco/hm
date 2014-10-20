<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/user.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
		<form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
			<table class="list">
			<thead>
				<tr>
				<td class="left"><?php echo $column_order_ref; ?></td>
				<td class="left"><?php echo $column_product_id; ?></td>
				<td class="left"><?php echo $column_product_name; ?></td>
				<td class="left"><?php echo $column_supplier; ?></td>
				<td class="left"><?php echo $column_quantity_ordered; ?></td>
				<td class="left"><?php echo $column_quantity_outstanding; ?></td>
				<td class="left"><?php echo $column_quantity_received; ?></td>
				<td></td>
				</tr>
			</thead>
			<tbody>
				<tr class="filter">
				<td><input type="text" id="filter_order_ref" name="filter_order_ref" value="<?php echo $filter_order_ref; ?>" /></td>
				<td><input type="text" name="filter_product_id" value="<?php echo $filter_product_id; ?>" /></td>
				<td><input type="text" id="filter_product_name" name="filter_product_name" value="<?php echo $filter_product_name; ?>" /></td>
				<td><select name="filter_supplier">
					<option value="*">&nbsp;</option>
					<?php if ($suppliers) {	?>
					<?php foreach ($suppliers as $supplier) { ?>
					<?php if ($supplier['id'] == $filter_supplier) { ?>
							<option value="<?php echo $supplier['id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
					<?php } else { ?>
							<option value="<?php echo $supplier['id']; ?>"><?php echo $supplier['name']; ?></option>
					<?php } ?>
					<?php } ?>
					<?php } ?>
					</select></td>
				<td></td>
				<td></td>
				<td></td>
				<td align="center"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
			</tr>
			<?php if ($products) { ?>
			<?php foreach ($products as $product) { ?>
			<tr>
				<td class="left"><a href="<?php echo $product['po_href']; ?>"><?php echo $product['order_ref']; ?></a></td>
				<td class="left"><?php echo $product['product_id']; ?></td>
				<td class="left"><?php echo $product['product_name']; ?>
					<?php foreach ($product['options'] as $product_option) { ?>
						<?php echo '<br><small> - ' . $product_option['stored_option_name'] . '</small>'; ?>
					<?php } ?>
				</td>
				<td class="left"><?php echo $product['supplier_name']; ?></td>
				<td class="left"><?php echo $product['quantity_ordered']; ?></td>
				<td class="left outstanding"><?php echo $product['quantity_outstanding']; ?></td>
				<td class="left"><input type="text" name="outstanding[<?php echo $product['purchase_order_product_id']; ?>]" value="<?php echo $product['quantity_outstanding']; ?>"></td>
				<td class="center"><a class="update_button button" data-pop_id="<?php echo $product['purchase_order_product_id']; ?>"><span><?php echo $button_update; ?></span></a></td>
			</tr>
			<?php } ?>
			<?php } else { ?>
			<tr>
				<td class="center" colspan="9"><?php echo $text_no_results; ?></td>
			</tr>
				<?php } ?>
			</tbody>
			</table>
		</form>
	</div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=catalog/stock_received&token=<?php echo $token; ?>';

	var filter_order_ref = $('input[name=\'filter_order_ref\']').attr('value');

	if (filter_order_ref) {
		url += '&filter_order_ref=' + encodeURIComponent(filter_order_ref);
	}

	var filter_supplier = $('select[name=\'filter_supplier\']').val();

	if (filter_supplier != '*') {
		url += '&filter_supplier=' + encodeURIComponent(filter_supplier);
	}

	var filter_product_id = $('input[name=\'filter_product_id\']').attr('value');

	if (filter_product_id) {
		url += '&filter_product_id=' + encodeURIComponent(filter_product_id);
	}

	var filter_product_name = $('input[name=\'filter_product_name\']').attr('value');

	if (filter_product_name) {
		url += '&filter_product_name=' + encodeURIComponent(filter_product_name);
	}

	location = url;
}

$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});

$('.update_button').click(function() {
	var thisButton = $(this);
	var thisCell = $(this).closest('td');
	var thisRow = $(this).closest('tr');

	

	var purchase_order_product_id = $(this).attr('data-pop_id');
	var quantity_received = $('input[name=\'outstanding[' + purchase_order_product_id + ']\']').val();

	
	if (!quantity_received.match(/^[0-9]+$/) || parseInt(quantity_received) < 1) {
		$('input', thisRow).css('backgroundColor', '#f00');
		return;
	}

	$('input', thisRow).css('backgroundColor', '#FFF');
	
	thisButton.hide();
	thisCell.append('<img src="/admin/view/image/loading.gif">');

	var data = {
		'purchase_order_product_id': purchase_order_product_id,
		'quantity_received': quantity_received
	};

	$.ajax({
		url: 'index.php?route=catalog/stock_received/update&token=<?php echo $token; ?>',
		type: 'POST',
		data: data,
		dataType: 'json',
		success: function(data) {
			if (data.status == 'ok') {
				$('.outstanding', thisRow).html(data.outstanding + '<img src="/admin/view/image/ok.png" height="14" style="margin-left: 10px;">');
				$('img', thisCell).remove();
				thisButton.fadeIn();
			} else {
				$('.outstanding img', thisRow).remove();
				$('.outstanding', thisRow).append('<img src="/admin/view/image/cancel.png" height="14" style="margin-left: 10px;">');
				alert(data.status);
				$('img', thisCell).remove();
				thisButton.show();
			}
		}
	});
})

$(document).ready(function() {
	$('#filter_product_name').autocomplete(
		"index.php?route=catalog/product/autocompleteproductname&token=<?php echo $token; ?>", {
		dataType: 'json',
		parse: function(data) {
             var array = new Array();
             for(var i = 0; i < data.length; i++) {
                array[array.length] = { data: data[i], value: data[i], result: data[i] };
				
             }
             return array;
		},
		formatItem: function(row) {
			return row;
		}
	});
	$('#filter_order_ref').autocomplete(
		"index.php?route=catalog/purchase_order/autocompleteorderref&token=<?php echo $token; ?>", {
		dataType: 'json',
		parse: function(data) {
             var array = new Array();
             for(var i = 0; i < data.length; i++) {
                array[array.length] = { data: data[i], value: data[i], result: data[i] };

             }
             return array;
		},
		formatItem: function(row) {
			return row;
		}
	});
})
//--></script>
<?php echo $footer; ?>
