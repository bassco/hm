<?php
class ControllerReportSaleSummary extends Controller { 
	public function index() {  
		$this->load->language('report/sale_summary');

		$this->document->setTitle($this->language->get('heading_title'));

		if (isset($this->request->get['filter_date_start'])) {
			$filter_date_start = $this->request->get['filter_date_start'];
		} else {
			$filter_date_start = date('Y-m-d', strtotime(date('Y') . '-' . date('m') . '-01'));
		}

		if (isset($this->request->get['filter_date_end'])) {
			$filter_date_end = $this->request->get['filter_date_end'];
		} else {
			$filter_date_end = date('Y-m-d');
		}
		
		if (isset($this->request->get['filter_group'])) {
			$filter_group = $this->request->get['filter_group'];
		} else {
			$filter_group = 'week';
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$filter_order_status_id = $this->request->get['filter_order_status_id'];
		} else {
			$filter_order_status_id = 0;
		}	
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_group'])) {
			$url .= '&filter_group=' . $this->request->get['filter_group'];
		}		

		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
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
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'] . $url, 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->load->model('report/sale');
		
		$this->data['orders'] = array();
		
		$data = array(
			'filter_date_start'	 => $filter_date_start, 
			'filter_date_end'	 => $filter_date_end, 
			'filter_order_status_id' => $filter_order_status_id,
			'start'                  => ($page - 1) * $this->config->get('config_admin_limit'),
			'limit'                  => $this->config->get('config_admin_limit')
		);
		
		$results = $this->model_report_sale->getOrderSummary($data);
		
		foreach ($results as $result) {
			$this->data['orders'][] = array(
				'period' => date($this->language->get('date_format_short'), strtotime($result['period'])),
				'month'  => $result['month'],
				'year'   => $result['year'],
				'orders'   => $result['orders'],
				'orders_added'   => $result['orders_added'],
				'order_customers'   => $result['order_customers'],
				'customers'   => $result['customers'],
				'cust_added'   => $result['cust_added'],
				'monthly_sales'    => $this->currency->format($result['monthly_sales'], $this->config->get('config_currency')),
				'monthly_sales_avg'    => $this->currency->format($result['monthly_sales_avg'], $this->config->get('config_currency')),
				'sales'    => $this->currency->format($result['sales'], $this->config->get('config_currency')),
				'sales_avg'    => $this->currency->format($result['sales_avg'], $this->config->get('config_currency')),
			);
		}

		$this->data['heading_title'] = $this->language->get('heading_title');
		
		$this->data['text_no_results'] = $this->language->get('text_no_results');
		$this->data['text_all_status'] = $this->language->get('text_all_status');
		
		$this->data['column_period'] = $this->language->get('column_period');
		$this->data['column_year'] = $this->language->get('column_year');
         	$this->data['column_month'] = $this->language->get('column_month');
		$this->data['column_orders'] = $this->language->get('column_orders');
		$this->data['column_orders_added'] = $this->language->get('column_orders_added');
		$this->data['column_orders_added'] = $this->language->get('column_orders_added');
		$this->data['column_orders_added'] = $this->language->get('column_orders_added');
		$this->data['column_order_customers'] = $this->language->get('column_order_customers');
		$this->data['column_customers'] = $this->language->get('column_customers');
		$this->data['column_cust_added'] = $this->language->get('column_cust_added');
		$this->data['column_monthly_sales'] = $this->language->get('column_monthly_sales');
		$this->data['column_monthly_sales_avg'] = $this->language->get('column_monthly_sales_avg');
		$this->data['column_sales'] = $this->language->get('column_sales');
		$this->data['column_sales_avg'] = $this->language->get('column_sales_avg');
		
		$this->data['entry_date_start'] = $this->language->get('entry_date_start');
		$this->data['entry_date_end'] = $this->language->get('entry_date_end');
		$this->data['entry_status'] = $this->language->get('entry_status');

		$this->data['button_filter'] = $this->language->get('button_filter');
		
		$this->data['token'] = $this->session->data['token'];
		
		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$url = '';
						
		if (isset($this->request->get['filter_date_start'])) {
			$url .= '&filter_date_start=' . $this->request->get['filter_date_start'];
		}
		
		if (isset($this->request->get['filter_date_end'])) {
			$url .= '&filter_date_end=' . $this->request->get['filter_date_end'];
		}
		
		if (isset($this->request->get['filter_order_status_id'])) {
			$url .= '&filter_order_status_id=' . $this->request->get['filter_order_status_id'];
		}
				
		$pagination = new Pagination();
		$pagination->total = $order_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_admin_limit');
		$pagination->text = $this->language->get('text_pagination');
		$pagination->url = $this->url->link('report/sale_summary', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');
			
		$this->data['pagination'] = $pagination->render();		

		$this->data['filter_date_start'] = $filter_date_start;
		$this->data['filter_date_end'] = $filter_date_end;		
		$this->data['filter_order_status_id'] = $filter_order_status_id;
				 
		$this->template = 'report/sale_summary.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}
}
?>
