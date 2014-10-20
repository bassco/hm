<?php 
class ControllerAccountNewsletter extends Controller {  
	public function index() {
		if (!$this->customer->isLogged()) {
	  		$this->session->data['redirect'] = $this->url->link('account/newsletter', '', 'SSL');
	  
	  		$this->redirect($this->url->link('account/login', '', 'SSL'));
    	} 
		
		$this->language->load('account/newsletter');
    	
		$this->document->setTitle($this->language->get('heading_title'));
				
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->model('account/customer');
			$newsletter=$this->request->post['newsletter'];
			$this->model_account_customer->editNewsletter($newsletter);
			
                        # Update MailChimp 
            if (isset($newletter)){
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

			    if ($this->config->get('mailchimp_extra_fields')) { 
				    $merge_vars = array(
					    'FNAME' => $this->customer->getFirstName(),
					    'LNAME' => $this->customer->getLastName()
				    );
			    } else {	
				    $merge_vars = array(
					    'FNAME' => '',
					    'LNAME' => ''
				    );
			    }
				
			    $api = new MCAPI($this->config->get('mailchimp_api_key'));

			    if (!$api->errorCode) {
                    if($newletter==0){
                        $delete_member=false;
                        $send_goodbye=true;
                        $send_notify=true;
            	        $api->listUnsubscribe($mailchimp_list_id, $email, $delete_member, $send_goodbye, $send_notify);
                    }else{
	                    $api->listSubscribe($mailchimp_list_id, $email, $merge_vars, $email_type, $double_optin, $update_existing);
                    }
                }
            }
            
            
			$this->session->data['success'] = $this->language->get('text_success');
			
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

      	$this->data['breadcrumbs'] = array();

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),       	
        	'separator' => false
      	); 

      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$this->data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_newsletter'),
			'href'      => $this->url->link('account/newsletter', '', 'SSL'),
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$this->data['heading_title'] = $this->language->get('heading_title');

    	$this->data['text_yes'] = $this->language->get('text_yes');
		$this->data['text_no'] = $this->language->get('text_no');
		
		$this->data['entry_newsletter'] = $this->language->get('entry_newsletter');
		
		$this->data['button_continue'] = $this->language->get('button_continue');
		$this->data['button_back'] = $this->language->get('button_back');

    	$this->data['action'] = $this->url->link('account/newsletter', '', 'SSL');
		
		$this->data['newsletter'] = $this->customer->getNewsletter();
		
		$this->data['back'] = $this->url->link('account/account', '', 'SSL');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/newsletter.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/newsletter.tpl';
		} else {
			$this->template = 'default/template/account/newsletter.tpl';
		}
		
		$this->children = array(
			'common/column_left',
			'common/column_right',
			'common/content_top',
			'common/content_bottom',
			'common/footer',
			'common/header'	
		);
						
		$this->response->setOutput($this->render());			
  	}
}
?>