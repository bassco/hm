<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<?php foreach ($orders as $order) { ?>
<div class="pageBreak">
    <h1><?php echo $order['text_delivery']; ?></h1>
    <table class="address">
        <tr class="heading">
            <td class="box-address" style="width:50%"><b>FROM</b></td>
            <td class="box-address" style="width:50%"><b>TO</b></td>
        </tr>
        <tr>
            <td class="box-address">
                <br />
                <?php echo $order['store_name']; ?><br />
                34 White Rd<br />
                7800, Plumstead<br />
                Western Cape<br />
                Sender: Andrew<br/>
                <?php echo $text_telephone; ?> <?php echo $order['store_telephone']; ?><br />
                <br />
            </td>

            <td class="box-address">
                <br />
                <?php echo $order['shipping_address']; ?><br />
                <?php echo $text_telephone; ?> <?php echo $order['telephone']; ?><br />
                <br />
            </td>
        </tr>
    </table>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <table class="address">
        <tr class="heading">
            <td class="box-address" style="width:50%"><b>DATA</b></td>
            <td class="box-address" style="width:50%"><b>DATA CAPTURE</b></td>
        </tr>
        <tr>
            <td class="data-l">
                Printed<br />
                EFT<br />
                Sent Date<br/>
                Sent Via<br/>
                Cost<br/>
                Captured<br/>
                Delivered<br/>
            </td>
            <td class="data">
            <?php echo date("d-m-Y"); ?>:<br/>
                <?php echo $order['payment_method']; ?>:<br/>
                __________________________:<br/>
                <?php echo $order['shipping_method']; ?>:<br/>
                __________________________:<br/>
                __________________________:<br/>
                __________________________:<br />
            </td>
        </tr>
    </table>
</div>
<?php } ?>
</body>
</html>
