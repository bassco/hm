<?php
class ControllerReportCategoryMissing extends Controller { 
	public function index() {   
		$this->load->language('report/missing');

		$this->document->setTitle(sprintf($this->language->get('heading_title'), "Category"));
		
    	if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.date_available';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$url = '';

        if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
						
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
       			'text'      => sprintf($this->language->get('heading_title'),"Category"),
			'href'      => $this->url->link('report/category_missing', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      			'separator' => ' :: '
   		);		
		
		$this->load->model('report/missing');
		
		$this->data['products'] = array();
		
		$data = array(
            		'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'           => $this->config->get('config_admin_limit')
		);
				
		$results = $this->model_report_missing->getMissingCategory($data);
		
		foreach ($results as $result) {
			$this->data['products'][] = array(
				'quantity'         => $result['quantity'],
				'model'            => $result['model'],
				'name'             => $result['name'],
				'date_available'   => $result['date_available'],
				'date_added'       => $result['date_added'],
				'stock_status'     => $result['status'],
				'href'             => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . (int)$result['product_id'], 'SSL'),
			);
		}
				
		$this->data['heading_title'] = sprintf($this->language->get('heading_title'),"Category");
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		
		$this->data['column_date_added'] = $this->language->get('column_date_added');
		$this->data['column_date_available'] = $this->language->get('column_date_available');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_stock_status'] = $this->language->get('column_stock_status');
		
		$this->data['token'] = $this->session->data['token'];
		
		$url = '';
        
        if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

        $this->data['sort_name'] = $this->url->link('report/category_missing', 'token=' . $this->session->data['token'] . '&sort=name' . $url, 'SSL');
		$this->data['sort_model'] = $this->url->link('report/category_missing', 'token=' . $this->session->data['token'] . '&sort=model' . $url, 'SSL');
        $this->data['sort_date_available'] = $this->url->link('report/category_missing', 'token=' . $this->session->data['token'] . '&sort=date_available' . $url, 'SSL');

		$url = '';
		
        if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/category_missing', 'token=' . $this->session->data['token'] . $url . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
		
    		$this->data['sort'] = $sort;
		$this->data['order'] = $order;
        
		$this->template = 'report/missing.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}	
}
?>
