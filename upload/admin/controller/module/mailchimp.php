<?php
/**
 * Mailchimp OpenCart Module, connects the MailChimp 
 * Newsletter services with your OpenCart store.
 *
 * @author		Ian Gallacher (www.opencartstore.com)
 * @version		1.5.3.x
 * @support		https://opencartstorecom.zendesk.com/home
 * @email		info@opencartstore.com
 */

class ControllerModuleMailchimp extends Controller {
	private $_error = array(); 
	
	public function __construct($register) 
	{	
		parent::__construct($register);
		@require_once('system/MCAPI.class.php');
	}

	/**
	 * Set default values for the modules when it is installed
	 * by the OpenCart system.
	 */
	public function install() 
	{
		$this->load->model('setting/setting');
		
		$settings = array(
			'mailchimp_double_optin' 	=> '1',
			'mailchimp_update_existing' => '1',
			'mailchimp_extra_fields'	=> '0',
			'mailchimp_email_type' 		=> '1',
			'mailchimp_webhooks'		=> '0',
			'mailchimp_webhooks_key' 	=> crypt(time())
		);
		
		$this->model_setting_setting->editSetting('mailchimp', $settings);
	}

	/**
	 * Remove any configured webhook from the MailChimp system
	 * when the module is uninstalled.
	 */
	public function uninstall() 
	{
		if (!$this->config->get('mailchimp_webhooks_key')) {
			$url = 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=module/mailchimp/webhook&key=' . $this->config->get('mailchimp_webhooks_key');
			$this->_set_webhook($this->config->get('mailchimp_api_key'), $this->config->get('mailchimp_list_id'), $url, 'remove');
		}
	}

	public function index() 
	{   
		$this->load->language('module/mailchimp');
		$this->load->model('setting/setting');
		$this->load->model('sale/customer_group');
		
		$this->document->setTitle($this->language->get('heading_title'));
						
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->_validate())) {
			$config = array(
				'mailchimp_api_key' 		=> $this->request->post['mailchimp_api_key'],
				'mailchimp_list_id'			=> $this->request->post['mailchimp_list_id'],
				'mailchimp_double_optin'	=> $this->request->post['mailchimp_double_optin'],
				'mailchimp_update_existing' => $this->request->post['mailchimp_update_existing'],
				'mailchimp_extra_fields'	=> $this->request->post['mailchimp_extra_fields'],
				'mailchimp_email_type'		=> $this->request->post['mailchimp_email_type'],
				'mailchimp_webhooks_key' 	=> $this->config->get('mailchimp_webhooks_key')
			);

			if (isset($this->request->post['mailchimp_module'])) {
				$config['mailchimp_module'] = $this->request->post['mailchimp_module'];
			}

			if ($this->request->post['mailchimp_webhooks']) {
				$config['mailchimp_webhooks'] = $this->request->post['mailchimp_webhooks'];
				$url = 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=module/mailchimp/webhook&key=' . $this->config->get('mailchimp_webhooks_key');
				$this->_set_webhook($config['mailchimp_api_key'], $config['mailchimp_list_id'], $url, 'add');
			} else {
				$url = 'http://' . $_SERVER['SERVER_NAME'] . '/index.php?route=module/mailchimp/webhook&key=' . $this->config->get('mailchimp_webhooks_key');
				$this->_set_webhook($config['mailchimp_api_key'], $config['mailchimp_list_id'], $url, 'remove');
			}

			$this->model_setting_setting->editSetting('mailchimp', $config);

			if (!$this->request->post['save_without_close']) {
				$this->session->data['success'] = $this->language->get('text_success');
				$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'));
			}

			$this->data['success'] = $this->language->get('text_success');
		}
				
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] 				= $this->language->get('text_enabled');
		$this->data['text_disabled'] 				= $this->language->get('text_disabled');
		$this->data['text_content_top'] 			= $this->language->get('text_content_top');
		$this->data['text_content_bottom'] 			= $this->language->get('text_content_bottom');		
		$this->data['text_column_left'] 			= $this->language->get('text_column_left');
		$this->data['text_column_right'] 			= $this->language->get('text_column_right');
		$this->data['text_settings'] 				= $this->language->get('text_settings');
		$this->data['text_sync'] 					= $this->language->get('text_sync');
		$this->data['text_webhooks'] 				= $this->language->get('text_webhooks');
		$this->data['text_help'] 					= $this->language->get('text_help');
		$this->data['text_settings_about'] 			= $this->language->get('text_settings_about');
		$this->data['text_sync_about'] 				= $this->language->get('text_sync_about');
		$this->data['text_webhooks_about'] 			= $this->language->get('text_webhooks_about');
		$this->data['text_version_status'] 			= $this->language->get('text_version_status');
		$this->data['text_version_number'] 			= $this->language->get('text_version_number');
		$this->data['text_author'] 					= $this->language->get('text_author');
		$this->data['text_mailchimp_support'] 		= $this->language->get('text_mailchimp_support');
		$this->data['text_mailchimp_sync_alert']	= $this->language->get('text_mailchimp_sync_alert');
		$this->data['text_html'] 					= $this->language->get('text_html');
		$this->data['text_plain_text'] 				= $this->language->get('text_plain_text');
		
		$this->data['entry_api_key'] 				= $this->language->get('entry_api_key');
		$this->data['entry_list_id'] 				= $this->language->get('entry_list_id');
		$this->data['entry_double_optin'] 			= $this->language->get('entry_double_optin');
		$this->data['entry_update_existing'] 		= $this->language->get('entry_update_existing');
		$this->data['entry_extra_fields'] 			= $this->language->get('entry_extra_fields');
		$this->data['entry_email_type'] 			= $this->language->get('entry_email_type');
		$this->data['entry_webhooks'] 				= $this->language->get('entry_webhooks');
		$this->data['entry_status'] 				= $this->language->get('entry_status');
		$this->data['entry_layout'] 				= $this->language->get('entry_layout');
		$this->data['entry_position'] 				= $this->language->get('entry_position');
		$this->data['entry_sort_order'] 			= $this->language->get('entry_sort_order');
		$this->data['entry_sync_customer_group'] 	= $this->language->get('entry_sync_customer_group');
		$this->data['entry_sync_all'] 				= $this->language->get('entry_sync_all');
		
		$this->data['button_save'] 					= $this->language->get('button_save');
		$this->data['button_save_close'] 			= $this->language->get('button_save_close');
		$this->data['button_cancel'] 				= $this->language->get('button_cancel');
		$this->data['button_add_module'] 			= $this->language->get('button_add_module');
		$this->data['button_remove'] 				= $this->language->get('button_remove');
		$this->data['button_start_sync'] 			= $this->language->get('button_start_sync');

        if (isset($this->_error['warning'])) {
        	$this->data['error_warning'] = $this->_error['warning'];
        } else {
            $this->data['error_warning'] = '';
		}

		if (!isset($this->data['success'])) {
            $this->data['success'] = '';
		}
		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/mailchimp', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('module/mailchimp', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');

		$this->data['token'] = $this->session->data['token'];

		if (isset($this->request->post['mailchimp_api_key'])) {
			$this->data['mailchimp_api_key'] = $this->request->post['mailchimp_api_key'];
		} else {
			$this->data['mailchimp_api_key'] = $this->config->get('mailchimp_api_key');
		}
		
		if (isset($this->request->post['mailchimp_double_optin'])) {
			$this->data['mailchimp_double_optin'] = $this->request->post['mailchimp_double_optin'];
		} else {
			$this->data['mailchimp_double_optin'] = $this->config->get('mailchimp_double_optin');
		}
		
		if (isset($this->request->post['mailchimp_update_existing'])) {
			$this->data['mailchimp_update_existing'] = $this->request->post['mailchimp_update_existing'];
		} else {
			$this->data['mailchimp_update_existing'] = $this->config->get('mailchimp_update_existing');
		}
		
		if (isset($this->request->post['mailchimp_extra_fields'])) {
			$this->data['mailchimp_extra_fields'] = $this->request->post['mailchimp_extra_fields'];
		} else {
			$this->data['mailchimp_extra_fields'] = $this->config->get('mailchimp_extra_fields');
		}

		if (isset($this->request->post['mailchimp_email_type'])) {
			$this->data['mailchimp_email_type'] = $this->request->post['mailchimp_email_type'];
		} else {
			$this->data['mailchimp_email_type'] = $this->config->get('mailchimp_email_type');
		}

		$this->data['sync_customer_groups'] = $this->model_sale_customer_group->getCustomerGroups();

		if (isset($this->request->post['mailchimp_webhooks'])) {
			$this->data['mailchimp_webhooks'] = $this->request->post['mailchimp_webhooks'];
		} else {
			$this->data['mailchimp_webhooks'] = $this->config->get('mailchimp_webhooks');
		}
		
		$this->data['modules'] = array();
		
		if (isset($this->request->post['mailchimp_module'])) {
			$this->data['modules'] = $this->request->post['mailchimp_module'];
		} elseif ($this->config->get('mailchimp_module')) { 
			$this->data['modules'] = $this->config->get('mailchimp_module');
		}
		
		$this->load->model('design/layout');
		
		$this->data['layouts'] = $this->model_design_layout->getLayouts();

		$this->template = 'module/mailchimp.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
		
		$this->response->setOutput($this->render());
	}
	
	/**
	 * Check that required fields have values.
	 * 
	 * @access private
	 * @return boolean
	 */
	private function _validate() 
	{
		if (!$this->user->hasPermission('modify', 'module/mailchimp')) {
			$this->_error['warning'] = $this->language->get('error_permission');
		}

		if ($this->request->post['mailchimp_api_key'] == '') {
			$this->_error['warning'] = "You must set MailChimp API Key.";
		}
			
		if (!isset($this->request->post['mailchimp_list_id'])) {
			$this->_error['warning'] = "You must select at least one MailChimp list.";
		}
		
		if (!$this->_error) {
			return TRUE;
		} else {
			return FALSE;
		}	
	}
	
	/**
	 * By default returns a count of all newsletter subscribers.
	 * If the $_POST['update'] variable is set will initiate sync
	 * with MailChimp.
	 * 
	 * @return json
	 */
	public function ajax_get_subscribers() 
	{	
		extract($_POST);

		$this->load->model('module/mailchimp');

		if ($sync_customer_group == 'ALL') $sync_customer_group = '';

		if ($sync_all == "false") {
			$subscribed = $this->model_module_mailchimp->get_newsletter_subscribers($sync_customer_group);
		} else {
			$subscribed = $this->model_module_mailchimp->get_all_customers($sync_customer_group);
		}

		if (isset($update) && isset($mailchimp_list_id) && isset($mailchimp_api_key)) {
			$unsubscribed = ($sync_all == "true") ? array() : $this->model_module_mailchimp->get_newsletter_unsubscribed($sync_customer_group);
			$this->_mailchimp_batch_update($subscribed['customers'], $unsubscribed, $mailchimp_list_id, $mailchimp_api_key);
		} else {
			echo json_encode($subscribed['total']);
		}
	} 
	
	/**
	 * Send subscribes and/or unsubscribes to the selected MailChimp 
	 * list.
	 * 
	 * @param array Subscribers
	 * @param array Unsubscribers
	 * @param string MailChimp list Id
	 * @param string MailChimp Api Key
	 *
	 * @return json
	 */
	private function _mailchimp_batch_update($subscribed, $unsubscribed, $mailchimp_list_id, $mailchimp_api_key) 
	{		
		$double_optin = false; // Important, if true then confirmation email is sent
		$update_existing = ($this->config->get('mailchimp_update_existing')) ? true : false;
		$response = array();
		$ret_val = array();

		$api = new MCAPI($mailchimp_api_key);
		
		if (!empty($subscribed)) {
			// Structure data into MailCHimp accepted format.
			$form_subscribed = array();
			foreach ($subscribed as $subscriber) {
				$form_subscribed[] = array(
					'FNAME' => $subscriber['firstname'], 
					'LNAME' => $subscriber['lastname'], 
					'EMAIL' => $subscriber['email']
				);	
			}
			$ret_val = $api->listBatchSubscribe($mailchimp_list_id, $form_subscribed, $double_optin, $update_existing);
		} 
		
		if (!empty($unsubscribed)) {
			$vals = $api->listBatchUnsubscribe($mailchimp_list_id, $unsubscribed, true, false, false);
		}
		
		if ($api->errorCode) {
			$response['error'] 	= true;
			$response['msg'] 	= $api->errorMessage;
		} else {
			$response = $ret_val;			
		}
		
		echo json_encode($response);
	}

	/**
	 * Return all MailChimp lists attached to the MailChimp API Key.
	 *
	 * @return json
	 */
	 public function ajax_get_available_lists() 
	 {
	 	if (isset($_POST['mailchimp_api_key'])) {
	 		$mailchimp_api_key = $_POST['mailchimp_api_key'];
	 		$lists = array();
	 		$ret_val = array();

	 		$api = new MCAPI($mailchimp_api_key);

	 		if (!$api->errorCode) {
	 			$lists = $api->lists(); // Get the lists
	 			
	 			if ($lists['total']) {
	 				// Clean up the data with just the list id, name and count.
	 				foreach ($lists['data'] as $list) {
	 					$ret_val[] = array(
		 					'id'			=> $list['id'],
	 						'name'			=> $list['name'],
	 						'member_count'	=> $list['stats']['member_count']
	 					);
	 				}
	 			}
	 		}
	 		echo json_encode($ret_val);
	 	}
	}

	/**
	 * Return the selected MailChimp List.
	 *
	 * @return json
	 */
	 public function ajax_get_selected_list() 
	 {
	 	echo json_encode($this->config->get('mailchimp_list_id'));
	 }

	/**
	 * Add or delete a webhook with the MailChimp service.
	 *
	 * @param string Mailchimp API Key
	 * @param string Mailchimp List Id
	 * @param string Call back URL to register with webhooks
	 * @param String Action switch
	 * 
	 * @return boolean 
	 */
	private function _set_webhook($mailchimp_api_key, $mailchimp_list_id, $callback_url, $action) 
	{
		if (isset($mailchimp_api_key) && isset($mailchimp_list_id) && isset($callback_url)) {			
			$callback_url = filter_var($callback_url, FILTER_SANITIZE_URL);
			$action = strtolower($action);

	 		$api = new MCAPI($mailchimp_api_key);
	 		$webhooks = $api->listWebhooks($mailchimp_list_id);

	 		$hook_exists = false;
	 		
	 		// Check if the hook already exists
	 		foreach ($webhooks as $webhook) {
	 			if ($webhook['url'] == $callback_url) {
	 				$hook_exists = true;
	 			}
	 		}
	 		
 			switch ($action) {
 				case 'add':
 					if (!$hook_exists) {
 						$params = array(
	 						'subscribe' 	=> true,
 							'unsubscribe' 	=> true,
 							'profile' 	 	=> false,
 							'cleaned'	 	=> true,
 							'upemail' 	 	=> false,
 							'campaign' 	 	=> false
 						);
 						$api->listWebhookAdd($mailchimp_list_id, $callback_url, $params, $sources = array());
 					}	
 					break;
 				case 'remove': 
 					if ($hook_exists) { 
						$api->listWebhookDel($mailchimp_list_id, $callback_url);
					}
 					break;
 				default:
 					break;
 			}
 			
 			return ($api->errorCode) ? $api->errorMessage : true;
		}
	}
}