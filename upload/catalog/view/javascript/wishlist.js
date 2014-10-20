function wishlistMTC(product_id, quantity) {
	quantity = typeof (quantity) !== 'undefined' ? quantity : 1;
	$.ajax({
		url: 'index.php?route=account/wishlist/wlremove&remove=' + product_id,
		type: 'post'
	});
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: 'product_id=' + product_id + '&quantity=' + quantity,
		dataType: 'json',
		success: function (json) {
			$('.success, .warning, .attention, .information, .error').remove();if (json['redirect']) {location = json['redirect']; }

			if (json['success']) {
				$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

				$('.success').fadeIn('slow');

				$('#cart-total').html(json['total']);

				$('html, body').animate({scrollTop: 0}, 'slow');

				$('#content .wishlist-info').load('index.php?route=account/wishlist/refresh');

				$('#wishlist-total').load('index.php?route=module/cart/atw');
						$('#wishlist-total').html(json['total']);

			}
		}
	});
};
