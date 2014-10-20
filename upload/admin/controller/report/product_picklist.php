<?php
class ControllerReportProductPicklist extends Controller { 
	public function index() {   
		$this->load->language('report/product_picklist');

		$this->document->setTitle($this->language->get('heading_title'));
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';
						
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/product_picklist', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/picklist');
		
		$this->data['products'] = array();
		
		$data = array(
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
				
		$product_total = $this->model_report_picklist->getTotalPicklist($data);

		$results = $this->model_report_picklist->getPicklist($data);
		
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'quantity'   => $result['quantity'],
				'model'      => $result['model'],
				'name'       => $result['name']
			);
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_name'] = $this->language->get('column_name');
		
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
						
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/product_picklist', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
		
		$this->template = 'report/product_picklist.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}	
}
?>
