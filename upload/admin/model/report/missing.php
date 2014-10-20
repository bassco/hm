<?php
class ModelReportMissing extends Model {
	public function getMissing($view, $data = array()) {

		$sql = "SELECT * FROM `" . DB_PREFIX . $view . "`";
		
		$sort_data = array(
			'model',
            		'date_available',
			'name'
		);	
			
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_available";	
		}
			
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
					
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

	public function getMissingCategory($data = array()) {
		return $this->getMissing("vw_missing_category", $data);
         
	}

	public function getMissingManufacturer($data = array()) {
		return $this->getMissing("vw_missing_manufacturer", $data);
         
	}
	
}
?>
