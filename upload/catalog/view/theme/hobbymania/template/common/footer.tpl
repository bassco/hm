<div id="footer">
  <div class="column">
    <h3><?php echo $text_account; ?></h3>
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_extra; ?></h3>
    <ul>
      <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
<!--      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li> -->
      <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li> 
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
    </ul>
  </div>
</div>
<div id="assoc_logos">
  <div class="column">
    <div id="thawteseal" title="Click to Verify - This site chose Thawte SSL for secure e-commerce and confidential communications.">
      <div><script type="text/javascript" src="https://seal.thawte.com/getthawteseal?host_name=hobbymania.co.za&amp;size=S&amp;lang=en"></script></div>
     <div><a href="http://www.thawte.com/ssl-certificates/" target="_blank" style="color:#000000; text-decoration:none; font:bold 10px arial,sans-serif; margin:0px; padding:0px;">ABOUT SSL CERTIFICATES</a></div>
    </div>
  </div>
  <div class="column">
    <a target="_blank" href="http://www.credit-card-logos.com"><img alt="MasterCard and Visa Logo" src="https://developer.mygateglobal.com/images/logos/mc_vs.png" height="40" width="120" /></a>
  </div>
  <div class="column">
    <a target="_blank" href="https://virtual.mygateglobal.com/verifiedSecureMerchantPage.cfm?ApplicationID=aa985f61-0427-43e9-a7bf-120ee425b5d2"> <img alt="MyGate-Secure-Merchant-Logo" src="https://developer.mygateglobal.com/images/logos/mygate-secure-merchant.png" height="40" width="120" /></a> 
  </div>
  <div class="column">
    <div id="powered"></div>
  </div>
</div>
</div>
</body></html>
