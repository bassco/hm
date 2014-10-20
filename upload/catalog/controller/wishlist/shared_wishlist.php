<?php 
class ControllerWishlistSharedWishList extends Controller {
	private $error = array();
	public function index() {

		$this->language->load('wishlist/shared_wishlist');
		$this->load->model('wishlist/shared_wishlist');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		if(isset($this->request->get['id'])) {
	            $customer_id = $this->request->get['id'];
		}else{
		        $customer_id = 0;
		}
		
		if(isset($this->request->get['name'])) {
	            $name = $this->request->get['name'];
		}else{
		        $name = 0;
		}
      	
		$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(       	
        	'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('wishlist/shared_wishlist&id=' . $customer_id . '&name=' . $name),
        	'separator' => $this->language->get('text_separator')
      	);
								
		$this->data['heading_title'] = $this->language->get('heading_title');	
		$this->data['page_title'] = sprintf($this->language->get('page_title'), strtoupper($name));	
		$this->data['text_empty'] = $this->language->get('text_empty');
     		
		$this->data['column_image'] = $this->language->get('column_image');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_stock'] = $this->language->get('column_stock');
		$this->data['column_price'] = $this->language->get('column_price');
		$this->data['column_action'] = $this->language->get('column_action');
					
		$this->data['button_cart'] = $this->language->get('button_cart');
		$this->data['button_continue'] = $this->language->get('button_continue');
		
		
		$this->data['products'] = array();
				
		$wishlist = $this->model_wishlist_shared_wishlist->getWishlist($customer_id, $name);
		
		if ($wishlist) {
	
		foreach ($wishlist as $product_id) {
			$product_info = $this->model_catalog_product->getProduct($product_id);
			
			if ($product_info) { 
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'], $this->config->get('config_image_wishlist_width'), $this->config->get('config_image_wishlist_height'));
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}
							
				if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}
				
				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}
																			
				$this->data['products'][] = array(
					'product_id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,		
					'special'    => $special,
					'href'       => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
				);
			}
		}	
	}

		$this->data['continue'] = $this->url->link('common/home');
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/wishlist/shared_wishlist.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/wishlist/shared_wishlist.tpl';
		} else {
			$this->template = 'default/template/wishlist/shared_wishlist.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
							
		$this->response->setOutput($this->render());		
	}
}
?>