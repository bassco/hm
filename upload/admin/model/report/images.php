<?php
class ModelReportImages extends Model {
	public function getImages($data = array()) {

		$sql = "SELECT p.product_id, p.model, p.date_available, pd.name, p.quantity, p.status FROM `" . DB_PREFIX . "product_description` pd LEFT JOIN `" . DB_PREFIX . "product` p ON (pd.product_id = p.product_id)";

		$sql .= " WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        $sql .= " AND (p.image = 'data/NoPhotoIcon.jpg' OR p.image ='')";

        $sort_data = array(
			'p.model',
            'p.date_available',
			'pd.name'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY p.status DESC, " . $data['sort'];
		} else {
			$sql .= " ORDER BY p.status DESC, p.quantity DESC ";
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

	public function getCountImages($data) {
      		$sql = "SELECT COUNT(p.product_id) AS total FROM `" . DB_PREFIX . "product` p WHERE (p.image = 'data/NoPhotoIcon.jpg' or p.image = '')";

		$query = $this->db->query($sql);

		return $query->row['total'];
	}
}
?>
