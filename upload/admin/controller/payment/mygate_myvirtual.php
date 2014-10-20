<?php
class ControllerPaymentMygateMyvirtual extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/mygate_myvirtual');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('mygate_myvirtual', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		$this->data['text_on'] = $this->language->get('text_on');
		$this->data['text_off'] = $this->language->get('text_off');

		$this->data['entry_merchant'] = $this->language->get('entry_merchant');
		$this->data['entry_application'] = $this->language->get('entry_application');
		$this->data['entry_callback'] = $this->language->get('entry_callback');
		$this->data['entry_test'] = $this->language->get('entry_test');
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

 		if (isset($this->error['merchant'])) {
			$this->data['error_merchant'] = $this->error['merchant'];
		} else {
			$this->data['error_merchant'] = '';
		}

 		if (isset($this->error['application'])) {
			$this->data['error_application'] = $this->error['application'];
		} else {
			$this->data['error_application'] = '';
		}

		$this->data['breadcrumbs'] = array();

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('text_home'),
			'separator' => FALSE
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('payment/mygate_myvirtual', 'token=' . $this->session->data['token'], 'SSL'),
			'text'      => $this->language->get('heading_title'),
			'separator' => ' :: '
		);

		$this->data['action'] = $this->url->link('payment/mygate_myvirtual', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['mygate_myvirtual_merchant'])) {
			$this->data['mygate_myvirtual_merchant'] = $this->request->post['mygate_myvirtual_merchant'];
		} else {
			$this->data['mygate_myvirtual_merchant'] = $this->config->get('mygate_myvirtual_merchant');
		}

		if (isset($this->request->post['mygate_myvirtual_application'])) {
			$this->data['mygate_myvirtual_application'] = $this->request->post['mygate_myvirtual_application'];
		} else {
			$this->data['mygate_myvirtual_application'] = $this->config->get('mygate_myvirtual_application');
		}

		if (isset($this->request->post['mygate_myvirtual_callback'])) {
			$this->data['mygate_myvirtual_callback'] = $this->request->post['mygate_myvirtual_callback'];
		} else {
			$this->data['mygate_myvirtual_callback'] = HTTPS_CATALOG . 'index.php?route=payment/mygate_myvirtual/callback';
		}

		if (isset($this->request->post['mygate_myvirtual_test'])) {
			$this->data['mygate_myvirtual_test'] = $this->request->post['mygate_myvirtual_test'];
		} else {
			$this->data['mygate_myvirtual_test'] = $this->config->get('mygate_myvirtual_test');
		}

		if (isset($this->request->post['mygate_myvirtual_order_status_id'])) {
			$this->data['mygate_myvirtual_order_status_id'] = $this->request->post['mygate_myvirtual_order_status_id'];
		} else {
			$this->data['mygate_myvirtual_order_status_id'] = $this->config->get('mygate_myvirtual_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['mygate_myvirtual_geo_zone_id'])) {
			$this->data['mygate_myvirtual_geo_zone_id'] = $this->request->post['mygate_myvirtual_geo_zone_id'];
		} else {
			$this->data['mygate_myvirtual_geo_zone_id'] = $this->config->get('mygate_myvirtual_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['mygate_myvirtual_status'])) {
			$this->data['mygate_myvirtual_status'] = $this->request->post['mygate_myvirtual_status'];
		} else {
			$this->data['mygate_myvirtual_status'] = $this->config->get('mygate_myvirtual_status');
		}

		if (isset($this->request->post['mygate_myvirtual_sort_order'])) {
			$this->data['mygate_myvirtual_sort_order'] = $this->request->post['mygate_myvirtual_sort_order'];
		} else {
			$this->data['mygate_myvirtual_sort_order'] = $this->config->get('mygate_myvirtual_sort_order');
		}

		$this->template = 'payment/mygate_myvirtual.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/mygate_myvirtual')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['mygate_myvirtual_merchant']) {
			$this->error['merchant'] = $this->language->get('error_merchant');
		}

		if (!$this->request->post['mygate_myvirtual_application']) {
			$this->error['application'] = $this->language->get('error_application');
		}

		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
