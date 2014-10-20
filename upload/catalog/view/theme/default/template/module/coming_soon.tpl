<?php if ($products) { ?>
<div class="box">
  <div class="box-heading"><a id="coming-soon"><?php echo $heading_title; ?></a></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></div>
        <?php } ?>
        <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
        <?php if ($product['price'] && $product['show_price']==0){ ?>
        <div class="price">
          <?php if (!$product['special']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
          <?php } ?>
        </div>
        <?php } ?>
        <div class="availability"><?php echo $product['date_available']; ?></div>
        <div class="cart"><a href="<?php echo $product['href']; ?>" class="button"><span><?php echo $button_coming_soon; ?></span></a></div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php } ?>
