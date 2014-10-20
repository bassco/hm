<?php

class ControllerCatalogPurchaseOrder extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('catalog/purchase_order');

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
			'href' => $this->url->link('catalog/purchase_order', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_order_ref'] = $this->language->get('column_order_ref');
		$this->data['column_supplier'] = $this->language->get('column_supplier');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');
		$this->data['button_filter'] = $this->language->get('button_filter');


		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/purchase_order/insert&token=' . $this->session->data['token'];
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/purchase_order/delete&token=' . $this->session->data['token'];

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

		if (isset($this->request->get['filter_supplier'])) {
			$filter_supplier = $this->request->get['filter_supplier'];
		} else {
			$filter_supplier = NULL;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = NULL;
		}

		$this->data['filter_order_ref'] = $filter_order_ref;
		$this->data['filter_supplier'] = $filter_supplier;
		$this->data['filter_status'] = $filter_status;

		$this->data['suppliers'] = array();

		$result = $this->model_catalog_supplier->getSuppliers();

		foreach ($result as $supplier) {
			$this->data['suppliers'][] = array(
				'id' => $supplier['supplier_id'],
				'name' => $supplier['name']
			);
		}

		$this->data['statuses'] = array();
		$this->data['statuses'][] = array(
			'id' => 1,
			'name' => $this->language->get('text_outstanding')
		);
		$this->data['statuses'][] = array(
			'id' => 2,
			'name' => $this->language->get('text_partially_received')
		);
		$this->data['statuses'][] = array(
			'id' => 3,
			'name' => $this->language->get('text_received')
		);

		$this->template = 'catalog/purchase_order_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$data = array(
			'filter_order_ref' => $filter_order_ref,
			'filter_supplier' => $filter_supplier,
			'filter_status' => $filter_status
		);

		$purchase_orders = $this->model_catalog_purchase_order->getPurchaseOrders($data);
		$this->data['purchase_orders'] = array();
		foreach ($purchase_orders as $purchase_order) {
			$purchase_order['action'] = array();
			$purchase_order['action'][] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/purchase_order/update&token=' . $this->session->data['token'] . '&purchase_order_id=' . $purchase_order['purchase_order_id']
			);
			$purchase_order['selected'] = isset($this->request->post['selected']) && in_array($purchase_orders['purchase_order_id'], $this->request->post['selected']);

			if ($purchase_order['outstanding'] == 0) {
				$purchase_order['status_text'] = $this->language->get('text_received');
			} else if ($purchase_order['outstanding'] == $purchase_order['ordered']) {
				$purchase_order['status_text'] = $this->language->get('text_outstanding');
			} else {
				$purchase_order['status_text'] = $this->language->get('text_partially_received');
			}

			$this->data['purchase_orders'][] = $purchase_order;
		}

		$this->data['token'] = $this->session->data['token'];

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	private function getForm() {

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href' => $this->url->link('catalog/purchase_order', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);
		
		$this->load->model('catalog/supplier');
		$this->load->model('catalog/category');
		$this->load->model('catalog/purchase_order');

		if (isset($this->request->get['purchase_order_id'])) {
			$purchase_order_id = $this->request->get['purchase_order_id'];
		} else {
			$purchase_order_id = 0;
		}

		$this->data['products'] = array();

		$products = $this->model_catalog_purchase_order->getPurchaseOrderProducts($purchase_order_id);
		foreach ($products as $product) {
			$product['description'] = html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8');
			$product['href'] = HTTPS_SERVER . 'index.php?route=catalog/product/update&token=' . $this->session->data['token'] . '&product_id=' . $product['product_id'];
			$product_options = $this->model_catalog_purchase_order->getPurchaseOrderProductOptionValues($product['purchase_order_product_id']);
			foreach ($product_options as $key => $product_option) {
				$product_options[$key]['stored_option_name'] = $product_option['product_option_original_name'] . " " . $product_option['product_option_value_original_name'];
			}
			$product['options'] = $product_options;
			$this->data['products'][] = $product;
		}


		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['entry_order_ref'] = $this->language->get('entry_order_ref');
		$this->data['entry_supplier'] = $this->language->get('entry_supplier');
		$this->data['entry_category'] = $this->language->get('entry_category');
		$this->data['entry_product'] = $this->language->get('entry_product');
		$this->data['entry_option'] = $this->language->get('entry_option');
		$this->data['entry_quantity_ordered'] = $this->language->get('entry_quantity_ordered');

		$this->data['column_product_id'] = $this->language->get('column_product_id');
		$this->data['column_product_name'] = $this->language->get('column_product_name');
		$this->data['column_product_description'] = $this->language->get('column_product_description');
		$this->data['column_quantity_ordered'] = $this->language->get('column_quantity_ordered');
		$this->data['column_quantity_outstanding'] = $this->language->get('column_quantity_outstanding');
		$this->data['column_add_product'] = $this->language->get('column_add_product');

		$this->data['text_no_products'] = $this->language->get('text_no_products');

		$this->data['language_id'] = $this->config->get('config_language_id');

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		if (!isset($this->request->get['purchase_order_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/purchase_order/insert&token=' . $this->session->data['token'];
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/purchase_order/update&token=' . $this->session->data['token'] . '&purchase_order_id=' . $this->request->get['purchase_order_id'];
		}

		$this->data['cancel'] = HTTPS_SERVER . 'index.php?route=catalog/purchase_order&token=' . $this->session->data['token'];

		if (isset($this->request->get['purchase_order_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$purchase_order = $this->model_catalog_purchase_order->getPurchaseOrder($this->request->get['purchase_order_id']);
		}

		if (isset($this->request->post['order_ref'])) {
			$this->data['order_ref'] = $this->request->post['order_ref'];
		} else if (isset($purchase_order)) {
			$this->data['order_ref'] = $purchase_order['order_ref'];
		} else {
			$this->data['order_ref'] = '';
		}

		if (isset($this->request->post['supplier_id'])) {
			$this->data['supplier_id'] = $this->request->post['supplier_id'];
		} else if (isset($purchase_order)) {
			$this->data['supplier_id'] = $purchase_order['supplier_id'];
		} else {
			$this->data['supplier_id'] = 0;
		}

		$this->data['suppliers'] = $this->model_catalog_supplier->getSuppliers();

		$this->data['categories'] = $this->model_catalog_category->getCategories(0);

		$this->data['token'] = $this->session->data['token'];

		$this->template = 'catalog/purchase_order_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function insert() {
		$this->load->language('catalog/purchase_order');

		$this->document->setTitle($this->language->get('heading_title')); 
		$this->load->model('catalog/purchase_order');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_purchase_order->addPurchaseOrder($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/purchase_order&token=' . $this->session->data['token']);
		}
		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/purchase_order');

		$this->document->setTitle($this->language->get('heading_title')); 
		$this->load->model('catalog/purchase_order');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_purchase_order->editPurchaseOrder($this->request->get['purchase_order_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/purchase_order&token=' . $this->session->data['token']);
		}
		$this->getForm();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/purchase_order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
			return FALSE;
		}
	}

	public function delete() {
		$this->load->language('catalog/purchase_order');

		$this->document->setTitle($this->language->get('heading_title')); 

		$this->load->model('catalog/purchase_order');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $purchase_order_id) {
				$this->model_catalog_purchase_order->deletePurchaseOrder($purchase_order_id);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/purchase_order&token=' . $this->session->data['token']);
		}

		$this->getList();
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/purchase_order')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function category() {
		$this->load->model('catalog/product');

		if (isset($this->request->get['category_id'])) {
			$category_id = $this->request->get['category_id'];
		} else {
			$category_id = 0;
		}

		$product_data = array();

		$results = $this->model_catalog_product->getProductsByCategoryId($category_id);

		foreach ($results as $result) {
			$product_data[$result['product_id']] = array(
				'name' => $result['name'],
				'model' => $result['model'],
				'href' => HTTPS_SERVER . 'index.php?route=catalog/product/update&token=' . $this->session->data['token'] . '&product_id=' . $result['product_id'],
				'description' => html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')
			);
		}

		$this->response->setOutput(json_encode($product_data));
	}

	public function product() {
		$this->load->model('catalog/product');

		if (isset($this->request->get['product_id'])) {
			$product_id = $this->request->get['product_id'];
		} else {
			$product_id = 0;
		}

		$option_data = array();

		$results = $this->model_catalog_product->getProductOptions($product_id);

		foreach ($results as $result) {

			$option_value_data = array();

			foreach ($result['product_option_value'] as $option_value) {
				$option_value_data[$option_value['product_option_value_id']] = array(
					'name' => $option_value['name'],
				);
			}

			$option_data[$result['product_option_id']] = array(
				'product_option_value' => $option_value_data,
				'name' => $result['name'],
			);
		}

		$this->response->setOutput(json_encode($option_data));
	}


	public function autoCompleteOrderRef() {

		$this->load->model('catalog/purchase_order');

		$data = array();
		$data['filter_order_ref'] = $this->request->get['q'];

		$purchase_orders = $this->model_catalog_purchase_order->getPurchaseOrders($data);

		$json = array();

		foreach ($purchase_orders as $purchase_order) {
			$json[] = html_entity_decode($purchase_order['order_ref'], ENT_QUOTES, 'UTF-8');
		}

		$this->response->setOutput(json_encode($json));
	}
}

?>
