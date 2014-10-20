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
  
class ControllerModuleMailChimp extends Controller {
	/**
	 * Main controller function that handles the modules required config
	 * , language and template loading.
	 */
	protected function index() 
	{	
		$this->load->language('module/mailchimp');

		$this->data['heading_title'] 		= $this->language->get('heading_title');
		$this->data['entry_email'] 			= $this->language->get('entry_email');
		$this->data['button_submit'] 		= $this->language->get('button_submit');
		$this->data['text_missing_field'] 	= $this->language->get('text_missing_field');
				
		if ($this->config->get('mailchimp_extra_fields')) {
			$this->data['extra_fields'] 	= $this->config->get('mailchimp_extra_fields');
			$this->data['entry_firstname'] 	= $this->language->get('entry_firstname');
			$this->data['entry_lastname'] 	= $this->language->get('entry_lastname');
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/mailchimp.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/module/mailchimp.tpl';
		} else {
			$this->template = 'default/template/module/mailchimp.tpl';
		}
		
		$this->render();
	}
	
	/**
	 * Send subscriber data to MailChimp.
	 * 
	 * @param string $_POST data
	 * @return string 
	 */
	public function ajax_send_subscribe() 
	{
		if (isset($_POST['email'])) {
			@require_once('system/library/MCAPI.class.php');
			
			$email 				= filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
			$mailchimp_list_id 	= $this->config->get('mailchimp_list_id');
			$double_optin 		= $this->config->get('mailchimp_double_optin');
			$update_existing 	= $this->config->get('mailchimp_update_existing'); 
			
			if ($this->config->get('mailchimp_email_type')) {
				$email_type = 'html';
			} else {
				$email_type = 'text';
			}
			
			$merge_vars 		= array();

			$this->load->language('module/mailchimp');		

			if ($this->config->get('mailchimp_extra_fields') && isset($_POST['firstname']) && isset($_POST['lastname'])) { 
				$merge_vars = array(
					'FNAME' => $_POST['firstname'],
					'LNAME' => $_POST['lastname']
				);
			} else {	
				$merge_vars = array(
					'FNAME' => '',
					'LNAME' => ''
				);
			}
				
			$api = new MCAPI($this->config->get('mailchimp_api_key'));

			if (!$api->errorCode) {
				if ($api->listSubscribe($mailchimp_list_id, $email, $merge_vars, $email_type, $double_optin, $update_existing)) {
					$response = array(
						'status'	=> TRUE,
						'message'	=> $this->language->get('text_thankyou')
					);
				} else {
					$response = array(
						'status' 	=> FALSE,
						'message'	=> $api->errorMessage
					);
				}
			} else {
				$response = array(
					'status' 	=> FALSE,
					'message'	=> $api->errorMessage
				);
			}
			echo json_encode($response);
		}
	}


	/**
	 * MailChimp Webhooks callback function. Called by MailChimp 
	 * when sending data back to OpenCart.
	 *
	 * @param string ($_POST)
	 */
	 public function webhook() 
	 {
	 	// If webhooks is enabled and that a valid key is set.
	 	if ($this->config->get('mailchimp_webhooks') && $this->config->get('mailchimp_webhooks_key')) {
	 		if (isset($_GET['key']) && $_GET['key'] == $this->config->get('mailchimp_webhooks_key')) {
	 			$this->load->model('module/mailchimp');

	 			$inbound_data = array(
		 			'type' 	=> $_POST['type'],
		 			'email'	=> filter_var($_POST['data']['email'], FILTER_VALIDATE_EMAIL)
		 		);

	 			switch ($inbound_data['type']) {
	 				case 'subscribe':
	 					$this->model_module_mailchimp->set_newsletter($inbound_data['email'], TRUE);
	 					break;
	 				case 'unsubscribe':
	 					$this->model_module_mailchimp->set_newsletter($inbound_data['email'], FALSE);
	 					break;
	 				case 'cleaned':
	 					$this->model_module_mailchimp->set_newsletter($inbound_data['email'], FALSE);
	 					break;
	 				default:
	 					break;
	 			}
	 		}
	 	}
	 }
}
?>