<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<title><?php echo $heading_title; ?></title>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
.wishlist-info table {
	width: 100%;
	border-collapse: collapse;
	border-top: 1px solid #DDDDDD;
	border-left: 1px solid #DDDDDD;
	border-right: 1px solid #DDDDDD;
	margin-bottom: 20px;
}
.wishlist-info td {
	padding: 7px;
}
.wishlist-info thead td {
	color: #4D4D4D;
	font-weight: bold;
	background-color: #F7F7F7;
	border-bottom: 1px solid #DDDDDD;
}
.wishlist-info thead .image {
	text-align: center;
}
.wishlist-info thead .name, .wishlist-info thead .model, .wishlist-info thead .stock {
	text-align: left;
}
.wishlist-info thead .quantity, .wishlist-info thead .price, .wishlist-info thead .total, .wishlist-info thead .action {
	text-align: right;
}
.wishlist-info tbody td {
	vertical-align: top;
	border-bottom: 1px solid #DDDDDD;
}
.wishlist-info tbody .image img {
	border: 1px solid #DDDDDD;
}
.wishlist-info tbody .image {
	text-align: center;
}
.wishlist-info tbody .name, .wishlist-info tbody .model, .wishlist-info tbody .stock {
	text-align: left;
}
.wishlist-info tbody .quantity, .wishlist-info tbody .price, .wishlist-info tbody .total, .wishlist-info tbody .action {
	text-align: right;
}
.wishlist-info tbody .price s {
	color: #F00;
}
.wishlist-info tbody .action img {
	cursor: pointer;
}
#content .content {
	padding: 10px;
	overflow: auto;
	margin-bottom: 20px;
	border: 1px solid #EEEEEE;
	width: 60%;
}
h1, .welcome {
	color: #000;
	font: Verdana;
	margin-top: 20px;
	margin-bottom: 20px;
	font-size: 24px;
	font-weight: normal;
	text-shadow: 0 0 1px rgba(0, 0, 0, .01);
}
h2 {
	color: #000000;
	font-size: 16px;
	margin-top: 0px;
	margin-bottom: 5px;
}

html {
	overflow: -moz-scrollbars-vertical;
	margin: 0;
	padding: 0;
}
body {
	background-color: #ffffff;
	color: #000000;
	font-family: Arial, Helvetica, sans-serif;
	margin: 0px;
	padding: 20px;
	width: 600px;
	margin: 0 auto;
}
body, td, th, input, textarea, select, a {
	font-size: 12px;
}
</style>
<script language="Javascript1.2">
  <!--
  function printpage() {
  window.print();
  }
  //-->
</script>
</head>
<body onload="printpage()">
<div><img src="<?php echo $logo; ?>" /></div>
<div id="content">
  <h1><?php echo $heading_title; ?></h1>
  <div><h2><?php echo $name; ?> - <?php echo $www; ?> - <?php echo $telephone; ?><br /><br />
</h2></div>
    <div style="float: left; margin: 10px 0 15px 0;"><a href="JavaScript:window.print();"><?php echo $button_print_page; ?></a></div>
    <div style="float: right; margin: 10px 0 15px 0;"><a href="JavaScript:window.close()">Close this Window</a></div>
	<div style="clear: both;"><div>
  <?php if ($products) { ?>
  <div class="wishlist-info">
    <table>
      <thead>
        <tr>
          <td class="image"><?php echo $column_image; ?></td>
          <td class="name"><?php echo $column_name; ?></td>
          <td class="model"><?php echo $column_model; ?></td>
          <td class="stock"><?php echo $column_stock; ?></td>
          <td class="price"><?php echo $column_price; ?></td>
          
            
        </tr>
      </thead>
      <?php foreach ($products as $product) { ?>
      <tbody id="wishlist-row<?php echo $product['product_id']; ?>">
        <tr>
          
<td class="image" style="border-bottom: none; padding-top: 40px; border-top: 3px solid #ddd"><?php if ($product['thumb']) { ?>
            
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          
<td class="name" style="border-bottom: none; padding-top: 40px; border-top: 3px solid #ddd"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
            
          
<td class="model" style="border-bottom: none; padding-top: 40px; border-top: 3px solid #ddd"><?php echo $product['model']; ?></td>
            
          
<td class="stock" style="border-bottom: none; padding-top: 40px; border-top: 3px solid #ddd"><?php echo $product['stock']; ?></td>
            
          
<td class="price" style="border-bottom: none; padding-top: 40px; border-top: 3px solid #ddd"><?php if ($product['price']) { ?>
            
            <div class="price">
              <?php if (!$product['special']) { ?>
              <?php echo $product['price']; ?>
              <?php } else { ?>
              <s><?php echo $product['price']; ?></s> <b><?php echo $product['special']; ?></b>
              <?php } ?>
            </div>
            <?php } ?></td>
          
		</tr>
		<tr>
          <td class="model" colspan="5" style="padding-bottom: 20px;"><?php echo $product['description']; ?></td>
        </tr>
	</tr>
        </tr>
      </tbody>
      <?php } ?>
    </table>
  </div>
	<div style="padding-bottom: 20px;"><div>
  </div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php } ?>
</body>