<?php
class ControllerModuleComingSoon extends Controller {

	protected function index($setting) {
		$classname = basename(__FILE__, '.php');

		$this->data['classname'] = $classname;

    	if ($setting['store_id'] != $this->config->get('config_store_id')) { return; }

		$this->data = array_merge($this->data, $this->load->language('module/' . $classname));

		$this->data['heading_title'] = $setting['language_id'][$this->config->get('config_language_id')];

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$this->data['products'] = array();

		$this->data['button_cart'] = $this->language->get('button_coming_soon');

		$this->load->model('catalog/coming_soon');

		$results = $this->model_catalog_coming_soon->getComingSoonProducts($setting['limit']);

		if ($results) {
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['image_width'], $setting['image_height']);
				} else {
					$image = false;
				}

				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}

				$stock_status = $result['stock_status'];
				$stock_qty = $result['quantity'];
				$has_stock = $result['has_stock'];

				if ($has_stock > 0 ) {
        				$stock_qty = 0;
      				}
				if ($has_stock == 1 ) {
       					$stock_status = "Coming Soon";
      				}
      				if ($has_stock == 2){
        				$stock_status = "Out Of Stock";
      				}


				$this->data['products'][] = array(
					'product_id'	=> $result['product_id'],
					'thumb'		=> $image,
					'name'		=> $result['name'],
					'price'   	=> $price,
    					'stock_status'       => $stock_status,
  					'stock_qty'       => $stock_qty,
    					'show_price'       => $has_stock,
					'special' 	=> $special,
					'rating'	=> $rating,
					'reviews'	=> sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'		=> $this->url->link('product/product', 'product_id=' . $result['product_id']),
					'date_available'=> date($this->language->get('date_format_short'), strtotime($result['date_available'])),
				);
			}
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/'.$classname.'.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/'.$classname.'.tpl';
		} else {
			$this->template = 'default/template/module/'.$classname.'.tpl';
		}

		$this->render();
  	}
}
?>
