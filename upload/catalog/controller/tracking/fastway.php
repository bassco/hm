<?php 
class ControllerTrackingFastway extends Controller {
	public function index() {  
		
	        $this->log->write("GET tracking request....");	
		$this->log->write(print_r($this->request->get,true));
	        $this->log->write("POST tracking request....");	
		$this->log->write(print_r($this->request->post, true));
   		$output = '{"error":"invalid key"}';

                if (isset($this->request->get['api_key'])) {
			if ($this->request->get['api_key'] == '32f150df30fbc45561757')
	   		$output = '{"success":"tracking data received"}';
		}

  		$this->response->setOutput($output);
	}


	
	public function info() {
		$this->load->model('catalog/information');
		
		if (isset($this->request->get['information_id'])) {
			$information_id = (int)$this->request->get['information_id'];
		} else {
			$information_id = 0;
		}      
		
		$information_info = $this->model_catalog_information->getInformation($information_id);

		if ($information_info) {
			$output  = '<html dir="ltr" lang="en">' . "\n";
			$output .= '<head>' . "\n";
			$output .= '  <title>' . $information_info['title'] . '</title>' . "\n";
			$output .= '  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">' . "\n";
			$output .= '  <meta name="robots" content="noindex">' . "\n";
			$output .= '</head>' . "\n";
			$output .= '<body>' . "\n";
			$output .= '  <h1>' . $information_info['title'] . '</h1>' . "\n";
			$output .= html_entity_decode($information_info['description'], ENT_QUOTES, 'UTF-8') . "\n";
			$output .= '  </body>' . "\n";
			$output .= '</html>' . "\n";			

			$this->response->setOutput($output);
		}
	}
}
?>
