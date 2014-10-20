<?php
class ControllerReportCustomerWishlisted extends Controller { 
	public function index() {  
	
		$this->load->model('report/wishlisted');
		$this->load->model('catalog/product');

		$this->load->language('report/customer_wishlisted');

		$this->document->setTitle($this->language->get('heading_title'));
						
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


   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('wishlisted'),
			'href'      => $this->url->link('report/product_wishlisted', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);		

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/customer_wishlisted', 'token=' . $this->session->data['token'] . '&wlitem=' . $item, 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/wishlisted');
				
		$this->data['customers'] = array();
		
		$wlcustomers = $this->model_report_wishlisted->getCustomerWishlisted($item);
		
		$customer_total = $this->model_report_wishlisted->getCustomerTotal($item);

		foreach ($wlcustomers as $wlcustomer) {
			$results = $this->model_report_wishlisted->getwlCustomer($wlcustomer);
			
			  $action = array();
			  $action[] = array(
				'text' => ($results['firstname']) . ' ' .  ($results['lastname']),
				'href' => $this->url->link('sale/customer/update', 'token=' . $this->session->data['token'] . '&customer_id=' . $results['customer_id'], 'SSL')
			  );
			
			  $view = array();
			  $view[] = array(
				'text' => $this->language->get('view'),
				'href' => $this->url->link('report/customer_wishlisted_products', 'token=' . $this->session->data['token'] . '&customer_id=' . $results['customer_id'] . '&wlitem=' . $item, 'SSL'),
			  );
			
			$this->data['customers'][] = array(
				'customer_id'    => $results['customer_id'],
				'name'           => $action,
				'view'           => $view,
				'firstname'		 => $results['firstname'],
				'email'          => $results['email']
			);
		}	

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_name'] = $this->language->get('column_name');
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
			$this->data['customers'] = $this->array_sort($this->data['customers'], 'firstname', SORT_ASC);
			$this->data['customers'] = array_slice($this->data['customers'], (int)$data['start'], (int)$data['limit']);
		}
		
		$pagination = new Pagination();
		$pagination->total = $customer_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/customer_wishlisted', 'token=' . $this->session->data['token'] . '&wlitem=' . $item . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
				
		$this->template = 'report/customer_wishlisted.tpl';
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