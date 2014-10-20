<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/report.png" alt="" /> <?php echo $heading_title; ?></h1>
    </div>
    <div class="content">
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_model; ?></td>
            <td class="left"><?php echo $column_name; ?></td>
            <td class="right"><?php echo $column_quantity; ?></td>
            <td class="left"><?php echo $column_date_added; ?></td>
            <td class="left"><?php echo $column_date_available; ?></td>
            <td class="left"><?php echo $column_stock_status; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($products) { ?>
          <?php foreach ($products as $product) { ?>
          <tr>
            <td class="left"><a onclick="window.open('<?php echo $product['href']; ?>');"><?php echo $product['model']; ?></a></td>
            <td class="left"><?php echo $product['name']; ?></td>
            <td class="right"><?php echo $product['quantity']; ?></td>
            <td class="left"><?php echo $product['date_added']; ?></td>
            <td class="left"><?php echo $product['date_available']; ?></td>
            <td class="left"><?php echo $product['stock_status']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
