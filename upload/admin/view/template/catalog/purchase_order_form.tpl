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
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
	    <table class="form">
		<tr>
		    <td><span class="required">*</span> <?php echo $entry_order_ref; ?></td>
		    <td><input type="text" name="order_ref" value="<?php echo $order_ref; ?>"></td>
		</tr>
		<tr>
		    <td><span class="required">*</span> <?php echo $entry_supplier; ?></td>
		    <td><select name="supplier_id">
			    <?php foreach ($suppliers as $supplier) {
			    ?>
			    <?php if ($supplier['supplier_id'] == $supplier_id) {
			    ?>
				    <option value="<?php echo $supplier['supplier_id']; ?>" selected="selected"><?php echo $supplier['name']; ?></option>
			    <?php } else {
			    ?>
				    <option value="<?php echo $supplier['supplier_id']; ?>"><?php echo $supplier['name']; ?></option>
			    <?php } ?>
			    <?php } ?>
			</select>
		    </td>
		</tr>
	    </table>
	    <table class="list" id="product_list">
		<thead>
		    <tr>
			<td class="left" nowrap><?php echo $column_product_id; ?></td>
			<td class="left" nowrap><?php echo $column_product_name; ?></td>
			<td class="left" nowrap><?php echo $column_product_description; ?></td>
			<td class="left" nowrap><?php echo $column_quantity_ordered; ?></td>
			<td class="left" nowrap><?php echo $column_quantity_outstanding; ?></td>
			<td>&nbsp;</td>
		    </tr>
		</thead>
		<tbody>
		    <?php

			    if ($products) {

				foreach ($products as $product) {
				    $purchase_order_product_id = "x" . $product['purchase_order_product_id'];
		    ?>
				    <tr>
					<td class="left" valign="top"><input type="hidden" name="product[<?php echo $purchase_order_product_id; ?>][product_id]" value="<?php echo $product['product_id']; ?>"><a href="<?php echo $product['href']; ?>"><?php echo $product['product_id']; ?></a></td>
					<td class="left" valign="top" nowrap><a href="<?php echo $product['href']; ?>"><?php echo $product['product_name']; ?></a>
					<?php foreach ($product['options'] as $product_option) { ?>
					<?php echo '<br><small> - ' . $product_option['stored_option_name'] . '</small>'; ?>
					<?php } ?></td>
					<td class="left" valign="top"><div style="max-height:200px;overflow:auto"><?php echo $product['description']; ?></div></td>
					<td class="left" valign="top"><input type="text" name="product[<?php echo $purchase_order_product_id; ?>][ordered]" value="<?php echo $product['quantity_ordered']; ?>"></td>
					<td class="left" valign="top"><input type="text" name="product[<?php echo $purchase_order_product_id; ?>][outstanding]" value="<?php echo $product['quantity_outstanding']; ?>"></td>
					<td class="left" valign="top"><a class="button removerow"><span>Remove</span></a></td>
				    </tr>
		    <?php

				}
			    } else {
		    ?>
	    		    <tr id="no_products"><td colspan="7" class="center"><?php echo $text_no_products; ?></td></tr>
		    <?php } ?>
			</tbody>
		    </table>
		    <table class="list">
			<thead>
			    <tr>
				<td class="left" colspan="3"><?php echo $column_add_product; ?></td>
			    </tr>
			</thead>
			<tbody>
			    <tr>
				<td class="left"><?php echo $entry_category; ?></td>
				<td class="left">
				    <select id="category" style="width: 450px;" onchange="getProducts();">
				<?php foreach ($categories as $category) {
				?>
    				<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
				<?php } ?>
			    </select>
			</td>
		    </tr>
		    <tr>
			<td class="left"><?php echo $entry_product; ?></td>
			<td class="left"><select id="products" style="width: 450px;" onchange="getOptions();"></select></td>
		    </tr>
		    <tr>
			<td class="left"><?php echo $entry_option; ?></td>
			<td class="left"><select multiple="multiple" id="option" size="5" style="width: 450px;"></select></td>
		    </tr>
		    <tr>
			<td class="left"><?php echo $entry_quantity_ordered; ?></td>
			<td class="left"><input id="add_quantity" name="add_quantity" type="text" value="1" size="5"/> <span class="add" onclick="addProduct();">&nbsp;</span></td>

		    </tr>
		</tbody>
	    </table>
	</form>
    </div>
</div>
<script type="text/javascript">
    var rows = 0;
    var product_options = '';
    var products = '';
    $(document).ready(function() {
	$('.removerow').live('click', function() {
	    $(this).closest('tr').fadeOut('slow', function(){
		$(this).remove();
	    })
	})
    });
    function getProducts() {
	$('#products option').remove();

	$.ajax({
	    url: 'index.php?route=catalog/purchase_order/category&token=<?php echo $token; ?>&category_id=' + $('#category').attr('value'),
	    dataType: 'json',
	    beforeSend: function() {
		$('#loading').remove();
		$('#products').after('&nbsp;<img id="loading" src="view/image/loading.gif" alt="" />');
	    },
	    success: function(data) {
		$('#loading').remove();
		for (i in data) {
		    $('#products').append('<option value="' + i + '">' + data[i]['name'] + ' [' + data[i]['model'] + ']</option>');
		}
		products = data;
		getOptions();
	    }
	});
    }

    function getOptions() {
	$('#option optgroup').remove();
	$('#option option').remove();

	$.ajax({
	    url: 'index.php?route=catalog/purchase_order/product&token=<?php echo $token; ?>&product_id=' + $('#products').attr('value'),
	    dataType: 'json',
	    beforeSend: function() {
		$('#loading').remove();
		$('#option').after('&nbsp;<img id="loading" src="view/image/loading.gif" alt="" />');
	    },
	    success: function(data) {
		$('#loading').remove();
		for (i in data) {
		    $('#option').append('<optgroup id="optgroup_'+i+'" label="' + data[i]['name'] + '"></optgroup>');
		    for (j in data[i]['product_option_value']) {
			$('#optgroup_'+i).append('<option value="' + j + '">' + data[i]['product_option_value'][j]['name'] + '</option>');
		    }
		}
		product_options = data;
	    }
	});
    }
    function addProduct() {

	var productID = $('#products').val();
	var product_name = products[productID]['name'];
	var product_href = products[productID]['href'];
	var product_description = products[productID]['description'];
	var optionsText = '';
	var optionsInfo = '';

	if ($('#option option:selected').length) {

	    $('#option option:selected').each(function() {
		var option_id = $(this).parent().attr('id').replace(/^optgroup\_/, '');
		var option_value_id = $(this).val();
		var option_name = product_options[option_id]['name'];

		var option_value_name = product_options[option_id]['product_option_value'][option_value_id]['name'];

		optionsText += '<br><small> - ' + option_name + ' ' + option_value_name + '</small>';
		optionsInfo += '<input type="hidden" name="product[' + rows + '][option][' + option_id + '][name]" value="' + option_name + '">';
		optionsInfo += '<input type="hidden" name="product[' + rows + '][option][' + option_id + '][options][' + option_value_id + '][name]" value="' + option_value_name + '">';

	    });

	}

	var html = '';
	html += '<tr style="display:none">';
	html += '<td valign="top" class="left">';
	html += '<input type="hidden" name="product[' + rows + '][product_id]" value="' + productID + '">';
	html += optionsInfo + productID + '</td>';
	html += '<td valign="top" class="left" nowrap><a href="' + product_href + '">' + product_name + '</a>'  + optionsText + '</td>';
	html += '<td valign="top" class="left"><div style="max-height:200px;overflow:auto">' + product_description + '</div></td>';
	html += '<td valign="top" class="left"><input type="text" value="' + $('#add_quantity').val() + '" name="product[' + rows + '][ordered]"></td>';
	html += '<td valign="top" class="left"><input type="text" value="' + $('#add_quantity').val() + '" name="product[' + rows + '][outstanding]"></td>';
	html += '<td valign="top" class="left"><a class="button removerow"><span>Remove</span></a></td>';
	html += '</tr>';

	$('#no_products').remove();
	$('#product_list tbody').append(html);
	$('#product_list tbody tr:last').fadeIn();
	rows++;

    }
    getProducts();
</script>
<style type="text/css">
.add {
    -moz-background-inline-policy: continuous;
    background: url("view/image/add.png") no-repeat scroll right center transparent;
    color: #000000;
    cursor: pointer;
    display: inline-block;
    padding-right: 20px;
}
</style>
<?php echo $footer; ?>
