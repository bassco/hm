<?php
class ModelPaymentMygateMyvirtual extends Model {
	public function getMethod($address) {
		$this->load->language('payment/mygate_myvirtual');

		if ($this->config->get('mygate_myvirtual_status')) {
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('mygate_myvirtual_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

			if (!$this->config->get('mygate_myvirtual_geo_zone_id')) {
				$status = TRUE;
			} elseif ($query->num_rows) {
				$status = TRUE;
			} else {
				$status = FALSE;
			}
		} else {
			$status = FALSE;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'         => 'mygate_myvirtual',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('mygate_myvirtual_sort_order')
			);
		}

		return $method_data;
	}
}
?>