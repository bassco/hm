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
      <table class="form">
        <tr>
          <td><?php echo $entry_date_start; ?>
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date-start" size="12" /></td>
          <td><?php echo $entry_date_end; ?>
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date-end" size="12" /></td>
          <td style="text-align: right;"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
        </tr>
      </table>
      <table class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $column_period; ?></td>
            <td class="left"><?php echo $column_month; ?></td>
            <td class="right"><?php echo $column_year; ?></td>
            <td class="right"><?php echo $column_orders; ?></td>
            <td class="right"><?php echo $column_orders_added; ?></td>
            <td class="right"><?php echo $column_order_customers; ?></td>
            <td class="right"><?php echo $column_customers; ?></td>
            <td class="right"><?php echo $column_cust_added; ?></td>
            <td class="right"><?php echo $column_monthly_sales; ?></td>
            <td class="right"><?php echo $column_monthly_sales_avg; ?></td>
            <td class="right"><?php echo $column_sales; ?></td>
            <td class="right"><?php echo $column_sales_avg; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="left"><?php echo $order['period']; ?></td>
            <td class="left"><?php echo $order['month']; ?></td>
            <td class="right"><?php echo $order['year']; ?></td>
            <td class="right"><?php echo $order['orders']; ?></td>
            <td class="right"><?php echo $order['orders_added']; ?></td>
            <td class="right"><?php echo $order['order_customers']; ?></td>
            <td class="right"><?php echo $order['customers']; ?></td>
            <td class="right"><?php echo $order['cust_added']; ?></td>
            <td class="right"><?php echo $order['monthly_sales']; ?></td>
            <td class="right"><?php echo $order['monthly_sales_avg']; ?></td>
            <td class="right"><?php echo $order['sales']; ?></td>
            <td class="right"><?php echo $order['sales_avg']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="12"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/sale_summary&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date-start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date-end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<?php echo $footer; ?>
