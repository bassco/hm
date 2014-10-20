<?php

class ControllerCatalogStockReceived extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('catalog/stock_received');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/purchase_order');
		$this->load->model('catalog/supplier');

		$this->getList();
	}

	public function getList() {

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/stock_received', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_order_ref'] = $this->language->get('column_order_ref');
		$this->data['column_product_id'] = $this->language->get('column_product_id');
		$this->data['column_product_name'] = $this->language->get('column_product_name');
		$this->data['column_supplier'] = $this->language->get('column_supplier');
		$this->data['column_quantity_ordered'] = $this->language->get('column_quantity_ordered');
		$this->data['column_quantity_outstanding'] = $this->language->get('column_quantity_outstanding');
		$this->data['column_quantity_received'] = $this->language->get('column_quantity_received');

		$this->data['button_filter'] = $this->language->get('button_filter');
		$this->data['button_update'] = $this->language->get('button_update');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->request->get['filter_order_ref'])) {
			$filter_order_ref = $this->request->get['filter_order_ref'];
		} else {
			$filter_order_ref = NULL;
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = NULL;
		}

		if (isset($this->request->get['filter_product_name'])) {
			$filter_product_name = $this->request->get['filter_product_name'];
		} else {
			$filter_product_name = NULL;
		}

		if (isset($this->request->get['filter_supplier'])) {
			$filter_supplier = $this->request->get['filter_supplier'];
		} else {
			$filter_supplier = NULL;
		}


		$this->data['filter_order_ref'] = $filter_order_ref;
		$this->data['filter_product_id'] = $filter_product_id;
		$this->data['filter_product_name'] = $filter_product_name;
		$this->data['filter_supplier'] = $filter_supplier;

		$this->data['suppliers'] = array();

		$suppliers = $this->model_catalog_supplier->getSuppliers();

		foreach ($suppliers as $supplier) {
			$this->data['suppliers'][] = array(
				'id' => $supplier['supplier_id'],
				'name' => $supplier['name']
			);
		}

		$data = array(
			'filter_order_ref' => $filter_order_ref,
			'filter_product_id' => $filter_product_id,
			'filter_product_name' => $filter_product_name,
			'filter_supplier' => $filter_supplier
		);

		$products = $this->model_catalog_purchase_order->getOutstandingProducts($data);

		$this->data['products'] = array();

		foreach ($products as $product) {

			$product_options = $this->model_catalog_purchase_order->getPurchaseOrderProductOptionValues($product['purchase_order_product_id']);
			foreach ($product_options as $key => $product_option) {
				$product_options[$key]['stored_option_name'] = $product_option['product_option_original_name'] . " " . $product_option['product_option_value_original_name'];
			}
			$product['options'] = $product_options;
			$product['po_href'] = HTTPS_SERVER . 'index.php?route=catalog/purchase_order/update&token=' . $this->session->data['token'] . '&purchase_order_id=' . $product['purchase_order_id'];
			$this->data['products'][] = $product;
		}

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/stock_received_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function update() {

		$this->load->model('catalog/purchase_order');
		$this->load->model('catalog/product');

		$json = array(
			'status' => 'ok',
			'outstanding' => 0
		);

		
		$purchase_order_product_id = (int) $this->request->post['purchase_order_product_id'];

		$quantity = (int) $this->request->post['quantity_received'];

		$purchase_order_product = $this->model_catalog_purchase_order->getPurchaseOrderProduct($purchase_order_product_id);

		$purchase_order = $this->model_catalog_purchase_order->getPurchaseOrder($purchase_order_product['purchase_order_id']);
		
		// option information when the purchase order was created
		$purchase_order_product_options = $this->model_catalog_purchase_order->getPurchaseOrderProductOptionValues($purchase_order_product_id);

		$product_id = $purchase_order_product['product_id'];
		// current option information
		$result = $this->model_catalog_product->getProductOptions($product_id);
		$product_options = array();
		foreach ($result as $option) {
			$product_options[$option['product_option_id']] = array(
				'name' => $option['name'],
				'options' => array()
			);
			foreach ($option['product_option_value'] as $option_value) {
				$product_options[$option['product_option_id']]['options'][$option_value['product_option_value_id']] = array(
					'name' => $option_value['name']
				);
			}
		}

		if ($quantity > $purchase_order_product['quantity_outstanding']) {
			$json['status'] = 'The quantity you entered is too high';
		} else {
			foreach ($purchase_order_product_options as $option) {
				if (!isset($product_options[$option['product_option_id']]) ||
						$product_options[$option['product_option_id']]['name'] != $option['product_option_original_name'] ||
						!isset($product_options[$option['product_option_id']]['options'][$option['product_option_value_id']]) ||
						$product_options[$option['product_option_id']]['options'][$option['product_option_value_id']]['name'] != $option['product_option_value_original_name']) {
					$json['status'] = "The options on the ordered product no longer exist, or are different to the product's current options.\n\nPlease recreate this line in the purchase order.";
					break;
				}
			}
		}

		if ($json['status'] == "ok") {
			$this->model_catalog_purchase_order->deductPurchaseOrderProductOutstandingQuantity($purchase_order_product_id, $quantity);
			$this->model_catalog_product->addProductQuantity($product_id, $quantity);
			foreach ($purchase_order_product_options as $option) {
				$this->model_catalog_product->addProductOptionValueQuantity($option['product_option_value_id'], $quantity);
			}
			$result = $this->db->query("SELECT p.quantity, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = p.product_id AND pd.language_id = '" . $this->config->get('config_language_id') . "' WHERE p.product_id = " . (int) $product_id);

			$product = $result->row;

			$this->db->query("INSERT INTO " . DB_PREFIX . "stock_control (type, product_id, product_name, adjustment, new_quantity, notes, admin_id, date) VALUES (
			    'purchase_order',
			    " . (int)$product_id . ",
			    '" . $this->db->escape($product['name']) . "',
			    " . $quantity . ",
			    " . $product['quantity'] . ",
			    'Purchase order ref: " . $this->db->escape($purchase_order['order_ref']) . "',
			    '" . $this->session->data['user_id'] . "',
			    NOW())");
		    
			$json['outstanding'] = ($purchase_order_product['quantity_outstanding'] - $quantity);
		}

		$this->response->setOutput(json_encode($json));
	}

}

?>
