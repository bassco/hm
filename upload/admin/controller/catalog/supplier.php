<?php

class ControllerCatalogSupplier extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('catalog/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

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
			'href' => $this->url->link('catalog/supplier', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->document->breadcrumbs = array();

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_confirm'] = $this->language->get('text_confirm');

		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_status'] = $this->language->get('column_status');
		$this->data['column_action'] = $this->language->get('column_action');

		$this->data['button_insert'] = $this->language->get('button_insert');
		$this->data['button_delete'] = $this->language->get('button_delete');


		$this->data['insert'] = HTTPS_SERVER . 'index.php?route=catalog/supplier/insert&token=' . $this->session->data['token'];
		$this->data['delete'] = HTTPS_SERVER . 'index.php?route=catalog/supplier/delete&token=' . $this->session->data['token'];

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} elseif (isset($this->session->data['warning'])) {
			$this->data['error_warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$this->data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		$this->template = 'catalog/supplier_list.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$suppliers = $this->model_catalog_supplier->getSuppliers();
		$this->data['suppliers'] = array();
		foreach ($suppliers as $supplier) {
			$supplier['action'] = array();
			$supplier['action'][] = array(
				'text' => $this->language->get('text_edit'),
				'href' => HTTPS_SERVER . 'index.php?route=catalog/supplier/update&token=' . $this->session->data['token'] . '&supplier_id=' . $supplier['supplier_id']
			);
			$supplier['selected'] = isset($this->request->post['selected']) && in_array($suppliers['supplier_id'], $this->request->post['selected']);
			$this->data['suppliers'][] = $supplier;
		}

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
			'href' => $this->url->link('catalog/supplier', 'token=' . $this->session->data['token'], 'SSL'),
			'text' => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['entry_name'] = $this->language->get('entry_name');
		$this->data['cancel'] = $this->url->link('catalog/supplier', 'token=' . $this->session->data['token'], 'SSL');

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

		if (!isset($this->request->get['supplier_id'])) {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/supplier/insert&token=' . $this->session->data['token'];
		} else {
			$this->data['action'] = HTTPS_SERVER . 'index.php?route=catalog/supplier/update&token=' . $this->session->data['token'] . '&supplier_id=' . $this->request->get['supplier_id'];
		}

		if (isset($this->request->get['supplier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$supplier = $this->model_catalog_supplier->getSupplier($this->request->get['supplier_id']);
		}

		if (isset($this->request->post['name'])) {
			$this->data['name'] = $this->request->post['name'];
		} elseif (isset($supplier)) {
			$this->data['name'] = $supplier['name'];
		} else {
			$this->data['name'] = '';
		}

		if (isset($this->error['name'])) {
			$this->data['error_name'] = $this->error['name'];
		} else {
			$this->data['error_name'] = '';
		}

		$this->template = 'catalog/supplier_form.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
	}

	public function insert() {
		$this->load->language('catalog/supplier');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/supplier');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_supplier->addSupplier($this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/supplier&token=' . $this->session->data['token']);
		}
		$this->getForm();
	}

	public function update() {
		$this->load->language('catalog/supplier');

		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/supplier');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_supplier->editSupplier($this->request->get['supplier_id'], $this->request->post);
			$this->session->data['success'] = $this->language->get('text_success');
			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/supplier&token=' . $this->session->data['token']);
		}
		$this->getForm();
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'catalog/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((strlen($this->request->post['name'])) < 1) {
			$this->error['name'] = $this->language->get('error_name');
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
		$this->load->language('catalog/supplier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/supplier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			
			$success = true;
			
			foreach ($this->request->post['selected'] as $supplier_id) {
				if (!$this->model_catalog_supplier->deleteSupplier($supplier_id)) {
					$success = false;
				}
			}
			
			if ($success) {
				$this->session->data['success'] = $this->language->get('text_success');
			} else {
				$this->session->data['warning'] = $this->language->get('error_suppier_in_use');
			}

			$this->redirect(HTTPS_SERVER . 'index.php?route=catalog/supplier&token=' . $this->session->data['token']);
		}

		$this->getList();
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'catalog/supplier')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

}

?>
