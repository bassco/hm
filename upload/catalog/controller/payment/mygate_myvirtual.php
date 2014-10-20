<?php
class ControllerPaymentMygateMyvirtual extends Controller {
	protected function index() {
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['button_back'] = $this->language->get('button_back');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		foreach ($this->cart->getProducts() as $product) {
			$option_data = array();

			foreach ($product['option'] as $option) {
				$option_data[] = array(
				'product_option_value_id' => $option['product_option_value_id'],
				'name'                    => $option['name'],
				'value'                   => $option['value'],
				'prefix'                  => $option['prefix']
				);
			}

			$product_data[] = array(
				'product_id' => $product['product_id'],
				'name'       => $product['name'],
				'model'      => $product['model'],
				'option'     => $option_data,
				'download'   => $product['download'],
				'quantity'   => $product['quantity'],
				'subtract'   => $product['subtract'],
				'price'      => $product['price'],
				'total'      => $product['total'],
				//'tax'        => $this->tax->getRate($product['tax_class_id'])
			);
		}
		$this->data['products'] = $product_data;

		$this->load->library('encryption');

		$this->data['action'] = 'https://www.mygate.co.za/virtual/8x0x0/dsp_ecommercepaymentparent.cfm';

		$this->data['merchant'] = $this->config->get('mygate_myvirtual_merchant');
		$this->data['application'] = $this->config->get('mygate_myvirtual_application');
		$this->data['callback'] = str_replace('&amp;', '&', $this->config->get('mygate_myvirtual_callback'));
		$this->data['order_id'] = $order_info['order_id'];
		$this->data['amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		$this->data['currency'] = $order_info['currency_code'];
		$this->data['description'] = $this->config->get('config_name') . ' - #' . $order_info['order_id'];
		$this->data['name'] = $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'];

		if (!$order_info['payment_address_2']) {
			$this->data['address'] = array(
			$order_info['payment_address_1'],
			$order_info['payment_city'] . ' ' . $order_info['payment_postcode'],
			$order_info['payment_zone'],
			);
		} else {
			$this->data['address'] = array(
			$order_info['payment_address_1'],
			$order_info['payment_address_2'],
			$order_info['payment_city'] . ' ' . $order_info['payment_postcode'],
			$order_info['payment_zone'],
			);
		}

		$this->data['postcode'] = $order_info['payment_postcode'];
		$this->data['country_code'] = $order_info['payment_iso_code_2'];
		$this->data['country'] = $order_info['payment_country'];
		$this->data['telephone'] = $order_info['telephone'];
		$this->data['email'] = $order_info['email'];
		$this->data['test'] = $this->config->get('mygate_myvirtual_test');

		$this->data['ip_address'] = $this->request->server['REMOTE_ADDR'];

		if ($this->request->get['route'] != 'checkout/guest_step_3') {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/payment';
		} else {
			$this->data['back'] = HTTPS_SERVER . 'index.php?route=checkout/guest_step_2';
		}

		$this->id = 'payment';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mygate_myvirtual.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/mygate_myvirtual.tpl';
		} else {
			$this->template = 'default/template/payment/mygate_myvirtual.tpl';
		}

		$this->render();
	}

	public function callback() {
		if (isset($this->request->post['VARIABLE1'])) {
			$this->language->load('payment/mygate_myvirtual');

			$this->data['title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			if (!isset($this->request->server['HTTPS']) || ($this->request->server['HTTPS'] != 'on')) {
				$this->data['base'] = HTTP_SERVER;
			} else {
				$this->data['base'] = HTTPS_SERVER;
			}

			$this->data['charset'] = $this->language->get('charset');
			$this->data['language'] = $this->language->get('code');
			$this->data['direction'] = $this->language->get('direction');

			$this->data['heading_title'] = sprintf($this->language->get('heading_title'), $this->config->get('config_name'));

			$this->data['text_response'] = $this->language->get('text_response');
			$this->data['text_success'] = $this->language->get('text_success');
			$this->data['text_success_wait'] = sprintf($this->language->get('text_success_wait'), HTTPS_SERVER . 'index.php?route=checkout/success');
			$this->data['text_failure'] = $this->language->get('text_failure');

			if ($this->request->get['route'] != 'checkout/guest_step_3') {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/payment');
			} else {
				$this->data['text_failure_wait'] = sprintf($this->language->get('text_failure_wait'), HTTPS_SERVER . 'index.php?route=checkout/guest_step_2');
			}

			$this->data['text_message'] = '';

			$order_id = $this->request->post['VARIABLE1'];

			if(!$order_id) {
	    	die;
	    }

	    $this->load->model('checkout/order');

	    $order = $this->model_checkout_order->getOrder($order_id);

	    if(!$order) {
	    	die;
	    }

			$message = '';

			if (isset($$order_id)) {
				$message .= 'Order ID: ' . $$order_id . "\n";
			}

			if (isset($this->request->post['_RESULT'])) {
				$message .= 'Result: ';
				if($this->request->post['_RESULT'] < 0) {
					$message .= 'Failed';
				} else if($this->request->post['_RESULT'] == 0) {
					$message .= 'Success';
				} else if($this->request->post['_RESULT'] > 0) {
					$message .= 'Success, with errors';
				}
				$message .= "\n";
			}

			if (isset($this->request->post['_ERROR_SOURCE']) && $this->request->post['_ERROR_SOURCE']) {
				$message .= 'Error Source: ' . $this->request->post['_ERROR_SOURCE'] . "\n";
			}

			if (isset($this->request->post['_ERROR_MESSAGE']) && $this->request->post['_ERROR_MESSAGE']) {
				$message .= 'Error Message: ' . $this->request->post['_ERROR_MESSAGE'] . "\n";
			}

			if (isset($this->request->post['_ERROR_DETAIL']) && $this->request->post['_ERROR_DETAIL']) {
				$message .= 'Error Details: ' . $this->request->post['_ERROR_DETAIL'] . "\n";
			}

			if (isset($this->request->post['_BANK_ERROR_CODE']) && $this->request->post['_BANK_ERROR_CODE']) {
				$message .= 'Bank Error Code: ' . $this->request->post['_BANK_ERROR_CODE'] . "\n";
			}

			if (isset($this->request->post['_BANK_ERROR_MESSAGE']) && $this->request->post['_BANK_ERROR_MESSAGE']) {
				$message .= 'Bank Error Message: ' . $this->request->post['_BANK_ERROR_MESSAGE'] . "\n";
			}

			if (isset($this->request->post['_3DSTATUS']) && $this->request->post['_3DSTATUS']) {
				$message .= '3D Secure Status: ' . $this->request->post['_3DSTATUS'] . "\n";
			}

			if (isset($this->request->post['_PANHASHED']) && $this->request->post['_PANHASHED']) {
				$message .= 'Card Number: ' . $this->request->post['_PANHASHED'] . "\n";
			}

			if (isset($this->request->post['_CARDCOUNTRY']) && $this->request->post['_CARDCOUNTRY']) {
				$message .= 'Card Issuing Country: ' . $this->request->post['_CARDCOUNTRY'] . "\n";
			}

			$this->model_checkout_order->update($order_id, $order['order_status_id'], $message, FALSE);

			if (isset($this->request->post['_RESULT']) && $this->request->post['_RESULT'] >= 0) {
				$this->model_checkout_order->confirm($order_id, $this->config->get('mygate_myvirtual_order_status_id'));

				$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/success';

				if (isset($this->request->post['_ERROR_MESSAGE']) && $this->request->post['_ERROR_MESSAGE']) {
					$this->data['text_message'] .= 'Error Message: ' . $this->request->post['_ERROR_MESSAGE'] . "\n";
				}

				if (isset($this->request->post['_BANK_ERROR_MESSAGE']) && $this->request->post['_BANK_ERROR_MESSAGE']) {
					$this->data['text_message'] .= 'Bank Error Message: ' . $this->request->post['_BANK_ERROR_MESSAGE'] . "\n";
				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mygate_myvirtual_success.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/mygate_myvirtual_success.tpl';
				} else {
					$this->template = 'default/template/payment/mygate_myvirtual_success.tpl';
				}

				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
			} else {
				$this->data['continue'] = HTTPS_SERVER . 'index.php?route=checkout/cart';

				if (isset($this->request->post['_ERROR_MESSAGE']) && $this->request->post['_ERROR_MESSAGE']) {
					$this->data['text_message'] .= 'Error Message: ' . $this->request->post['_ERROR_MESSAGE'] . "\n";
				}

				if (isset($this->request->post['_BANK_ERROR_MESSAGE']) && $this->request->post['_BANK_ERROR_MESSAGE']) {
					$this->data['text_message'] .= 'Bank Error Message: ' . $this->request->post['_BANK_ERROR_MESSAGE'] . "\n";
				}

				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/mygate_myvirtual_failure.tpl')) {
					$this->template = $this->config->get('config_template') . '/template/payment/mygate_myvirtual_failure.tpl';
				} else {
					$this->template = 'default/template/payment/mygate_myvirtual_failure.tpl';
				}

				$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
			}
		}
	}
}
?>