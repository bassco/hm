<?php
class ControllerReportProductWishlisted extends Controller { 
	public function index() {  
	
		$this->load->model('report/wishlisted');
		$this->load->model('catalog/product');

		$this->load->language('report/product_wishlisted');

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

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/product_wishlisted', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);		
		
		$this->load->model('report/wishlisted');
		
		$this->data['products'] = array();
				
		$product_total = $this->model_report_wishlisted->getTotalWishlisted();

		$results = $this->model_report_wishlisted->getWishlisted();


		if ($results) {
			foreach ($results as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);
				  $action = array();
				  $action[] = array(
					'text' => $product_info['name'],
					'href' => $this->url->link('report/customer_wishlisted', 'token=' . $this->session->data['token'] . '&wlitem=' . $product_info['product_id'], 'SSL')
				  );
			
				if ($product_info) {
					$this->data['products_data'][] = array(
						'product_id' => $product_info['product_id'],
						'quantity'   => $this->model_report_wishlisted->getWishlistCount($product_id),
						'model'		 => $product_info['model'],
						'name'       => $action
					);
				}
			}	
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_name'] = $this->language->get('column_name');
		$this->data['column_model'] = $this->language->get('column_model');
		$this->data['column_quantity'] = $this->language->get('column_quantity');
		$this->data['column_total'] = $this->language->get('column_total');
		
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
			$this->data['products_data'] = $this->array_sort($this->data['products_data'], 'quantity', SORT_DESC);
			$this->data['products'] = array_slice($this->data['products_data'], (int)$data['start'], (int)$data['limit']);
		}
		
		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/product_wishlisted', 'token=' . $this->session->data['token'] . '&page={page}');
			
		$this->data['pagination'] = $pagination->render();		
				
		$this->template = 'report/product_wishlisted.tpl';
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