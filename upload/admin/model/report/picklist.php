<?php
class ModelReportPicklist extends Model {
	public function getPicklist($data = array()) {
		$sql = "SELECT op.name, op.model, SUM(op.quantity) AS quantity FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id)";
		
		$sql .= " WHERE o.order_status_id in ('1', '2')";
		
		$sql .= " GROUP BY op.model ORDER BY op.name DESC";
					
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}			

			if ($data['limit'] < 1) {
				$data['limit'] = $this->config->get('config_admin_limit');
			}	
			
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);
	
		return $query->rows;
	}
	
	public function getTotalPicklist($data) {
      		$sql = "SELECT SUM(DISTINCT op.quantity) AS total FROM `" . DB_PREFIX . "order_product` op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) WHERE o.order_status_id in ('1', '2')";
		
		$query = $this->db->query($sql);
				
		return $query->row['total'];
	}
}
?>
