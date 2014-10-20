<?php
class ControllerReportCustomerCartedProducts extends Controller { 
	public function index() {  
	
		$this->load->model('report/carts');
		$this->load->model('catalog/product');

		$this->load->language('report/customer_carted');

		$this->document->setTitle($this->language->get('heading_title_products'));
						
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_admin_limit');
		}
		
		if (isset($this->request->get['wlitem'])) {
			$item = $this->request->get['wlitem'];
		} else {
			$item = 0;
		}

		if (isset($this->request->get['customer_id'])) {
			$customer_id = $this->request->get['customer_id'];
		} else {
			$customer_id = 0;
		}

		if (isset($this->request->get['back'])) {
			$back = $this->request->get['back'];
		} else {
			$back = 0;
		}

   		$this->data['breadcrumbs'] = array();

		if ($back = 1) {
		
			$this->data['breadcrumbs'][] = array(
       			'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => false
	   		);
			
	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title_customers'),
				'href'      => $this->url->link('report/customer_carted', 'token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => ' :: '
	   		);		
	
	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title_products'),
				'href'      => $this->url->link('report/customer_carted_products', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . '&back=1', 'SSL'),
	      		'separator' => ' :: '
	   		);
	
		} else {

	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => false
	   		);
			
	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('carted'),
				'href'      => $this->url->link('report/product_carted', 'token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => ' :: '
	   		);		
	
	   		$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('report/customer_carted', 'token=' . $this->session->data['token'] . '&wlitem=' . $item, 'SSL'),
	      		'separator' => ' :: '
	   		);
			
   			$this->data['breadcrumbs'][] = array(
	       		'text'      => $this->language->get('heading_title_products'),
				'href'      => $this->url->link('report/customer_carted_products', 'token=' . $this->session->data['token'], 'SSL'),
	      		'separator' => ' :: '
	   		);		
		
		}
		
		$this->load->model('report/carts');
		$this->load->model('catalog/product');
				
		$this->data['products'] = array();
		
		$wlproducts = $this->model_report_carts->getCustCart($customer_id);
		
		$custname = $this->model_report_carts->getwlCustomer($customer_id);
		
		$this->data['customer_name'] = ($custname['firstname']) . ' ' .  ($custname['lastname']);
		$this->data['customer_link'] = $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id, 'SSL');

		$carted_total = $this->model_report_carts->getCartsTotal($customer_id);

		foreach ($wlproducts as $product_id => $qty) {
		
			$results = $this->model_report_carts->getProduct($product_id);
						
			  $action = array();
			  $action[] = array(
				'text' => $results['name'],
				'href' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $results['product_id'], 'SSL')
			  );
			
			$this->data['products'][] = array(
				'name'           => $action
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title_products');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_name'] = $this->language->get('column_product_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');
		$this->data['column_products_name']  = $this->language->get('column_product_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_action'] = $this->language->get('column_action');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->data['token'];
		
		$data = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit,
		);
		if ($results) {
			$this->data['products'] = $this->array_sort($this->data['products'], 'name', SORT_ASC);
			$this->data['products'] = array_slice($this->data['products'], (int)$data['start'], (int)$data['limit']);
		}
		
		$pagination = new Pagination();
		$pagination->total = $carted_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/customer_carted_products', 'token=' . $this->session->data['token'] . '&customer_id=' . $customer_id . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
				
		$this->template = 'report/customer_carted.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
	public function array_sort($array, $on, $order=SORT_ASC) {
	    $new_array = array();
	    $sortable_array = array();

	    if (count($array) > 0) {
	        foreach ($array as $k => $v) {
	            if (is_array($v)) {
	                foreach ($v as $k2 => $v2) {
	                    if ($k2 == $on) {
	                        $sortable_array[$k] = $v2;
	                    }
	                }
	            } else {
	                $sortable_array[$k] = $v;
	            }
	        }

	        switch ($order) {
	            case SORT_ASC:
	                asort($sortable_array);
	            break;
	            case SORT_DESC:
	                arsort($sortable_array);
	            break;
	        }
	
	        foreach ($sortable_array as $k => $v) {
	            $new_array[$k] = $array[$k];
	        }
	    }
	
	    return $new_array;
	}
}
?>
