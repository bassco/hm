<?php

class ModelCatalogPurchaseOrder extends Model {

	public function getPurchaseOrders($data = array()) {
		if ($data) {
			$sql = "SELECT po.*, SUM(quantity_ordered) AS ordered, SUM(quantity_outstanding) AS outstanding, s.name AS supplier_name, s.supplier_id AS supplier_id FROM " . DB_PREFIX . "purchase_order po LEFT JOIN " . DB_PREFIX . "purchase_order_product pop ON pop.purchase_order_id = po.purchase_order_id LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id WHERE 1";

			if (isset($data['filter_order_ref']) && !is_null($data['filter_order_ref'])) {
				$sql .= " AND LCASE(po.order_ref) LIKE '%" . $this->db->escape(strtolower($data['filter_order_ref'])) . "%'";
			}

			if (isset($data['filter_supplier']) && !is_null($data['filter_supplier'])) {
				$sql .= " AND po.supplier_id = '" . (int) $data['filter_supplier'] . "'";
			}

			$sql .= " GROUP BY purchase_order_id";

			if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
				if ($data['filter_status'] == 1) {
					$sql .= " HAVING outstanding = ordered";
				} else if ($data['filter_status'] == 3) {
					$sql .= " HAVING outstanding = 0";
				} else {
					$sql .= " HAVING outstanding > 0 AND outstanding < ordered";
				}
			}
		} else {
			$sql = "SELECT po.*, SUM(quantity_ordered) AS ordered, SUM(quantity_outstanding) AS outstanding, s.name AS supplier_name, s.supplier_id AS supplier_id FROM " . DB_PREFIX . "purchase_order po LEFT JOIN " . DB_PREFIX . "purchase_order_product pop ON pop.purchase_order_id = po.purchase_order_id LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id GROUP BY purchase_order_id";
		}

		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getPurchaseOrder($purchase_order_id) {
		$query = $this->db->query("SELECT po.purchase_order_id, po.order_ref, s.name AS supplier_name, s.supplier_id AS supplier_id FROM " . DB_PREFIX . "purchase_order po LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id WHERE po.purchase_order_id = '" . (int) $purchase_order_id . "'");
		return $query->row;
	}

	public function editPurchaseOrder($purchase_order_id, $data = array()) {

		// update order ref and supplier
		$sql = "UPDATE " . DB_PREFIX . "purchase_order SET
	order_ref = '" . $this->db->escape($data['order_ref']) . "',
	supplier_id = '" . (int) $data['supplier_id'] . "'
	WHERE purchase_order_id = '" . (int) $purchase_order_id . "'";
		$query = $this->db->query($sql);

		$do_not_delete_these_products = array();

		if (isset($data['product'])) {
			foreach ($data['product'] as $id => $product_info) {
				if (substr($id, 0, 1) == 'x') {
					$id = str_replace('x', '', $id);
					$do_not_delete_these_products[] = $id;

					$this->db->query("UPDATE " . DB_PREFIX . "purchase_order_product SET quantity_ordered = '" . (int) $product_info['ordered'] . "', quantity_outstanding = '" . (int) $product_info['outstanding'] . "' WHERE purchase_order_product_id = '" . (int) $id . "'");
				} else {
					$product_id = (int) $product_info['product_id'];
					$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product SET product_id = " . $product_id . ", purchase_order_id = '" . (int) $purchase_order_id . "', quantity_ordered = '" . (int) $product_info['ordered'] . "', quantity_outstanding = '" . (int) $product_info['outstanding'] . "'");
					$purchase_order_product_id = $this->db->getLastId();
					$do_not_delete_these_products[] = $purchase_order_product_id;

					if (isset($product_info['option'])) {
						foreach ($product_info['option'] as $option_id => $option_info) {
							foreach ($option_info['options'] as $option_value_id => $option_value_info) {
								$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product_option_value SET
									purchase_order_id = '" . (int) $purchase_order_id . "',
									purchase_order_product_id = '" . $purchase_order_product_id . "',
									product_option_id = '" . (int) $option_id . "',
									product_option_original_name = '" . $this->db->escape($option_info['name']) . "',
									product_option_value_id = '" . (int) $option_value_id . "',
									product_option_value_original_name = '" . $this->db->escape($option_value_info['name']) . "'");
							}
						}
					}
				}
			}
		}

		$query = "DELETE FROM " . DB_PREFIX . "purchase_order_product WHERE purchase_order_id = '" . (int) $purchase_order_id . "'";
		if (!empty($do_not_delete_these_products)) {
			$query .= " AND purchase_order_product_id NOT IN (" . join($do_not_delete_these_products, ',') . ")";
		}
		$this->db->query($query);
		
		$query = "DELETE FROM " . DB_PREFIX . "purchase_order_product_option_value WHERE purchase_order_id = '" . (int) $purchase_order_id . "'";
		if (!empty($do_not_delete_these_products)) {
			$query .= " AND purchase_order_product_id NOT IN (" . join($do_not_delete_these_products, ',') . ")";
		}
		
		$this->db->query($query);
	}

	public function addPurchaseOrder($data) {
		// update order ref and supplier
		$sql = "INSERT INTO " . DB_PREFIX . "purchase_order SET
		order_ref = '" . $this->db->escape($data['order_ref']) . "',
		supplier_id = '" . (int) $data['supplier_id'] . "'";
		$query = $this->db->query($sql);
		$purchase_order_id = $this->db->getLastId();

		if (isset($data['product'])) {
			foreach ($data['product'] as $id => $product_info) {

				$product_id = (int) $product_info['product_id'];
				$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product SET product_id = " . $product_id . ", purchase_order_id = '" . (int) $purchase_order_id . "', quantity_ordered = '" . (int) $product_info['ordered'] . "', quantity_outstanding = '" . (int) $product_info['outstanding'] . "'");
				$purchase_order_product_id = $this->db->getLastId();
				$do_not_delete_these_products[] = $purchase_order_product_id;

				if (isset($product_info['option'])) {
					foreach ($product_info['option'] as $option_id => $option_info) {
						foreach ($option_info['options'] as $option_value_id => $option_value_info) {
							$this->db->query("INSERT INTO " . DB_PREFIX . "purchase_order_product_option_value SET
								purchase_order_id = '" . (int) $purchase_order_id . "',
								purchase_order_product_id = '" . $purchase_order_product_id . "',
								product_option_id = '" . (int) $option_id . "',
								product_option_original_name = '" . $this->db->escape($option_info['name']) . "',
								product_option_value_id = '" . (int) $option_value_id . "',
								product_option_value_original_name = '" . $this->db->escape($option_value_info['name']) . "'");
						}
					}
				}
			}
		}
	}

	public function deletePurchaseOrder($purchase_order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order WHERE purchase_order_id = '" . (int) $purchase_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_product WHERE purchase_order_id = '" . (int) $purchase_order_id . "'");
		$this->db->query("DELETE FROM " . DB_PREFIX . "purchase_order_product_option_value WHERE purchase_order_id = '" . (int) $purchase_order_id . "'");
	}

	public function getPurchaseOrderProducts($purchase_order_id) {
		$sql = "SELECT pop.*, pd.name AS product_name, pd.description FROM " . DB_PREFIX . "purchase_order_product pop LEFT JOIN " . DB_PREFIX . "product_description pd ON pd.product_id = pop.product_id AND pd.language_id = '" . $this->config->get('config_language_id') . "' WHERE pop.purchase_order_id = '" . (int) $purchase_order_id . "'";
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getPurchaseOrderProductOptionValues($purchase_order_product_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "purchase_order_product_option_value WHERE purchase_order_product_id = " . (int) $purchase_order_product_id;
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getOutstandingProducts($data = array()) {
		$sql = "SELECT po.order_ref, po.supplier_id, s.name AS supplier_name, pd.name AS product_name, pop.* FROM " . DB_PREFIX . "purchase_order_product pop LEFT JOIN " . DB_PREFIX . "purchase_order po ON po.purchase_order_id = pop.purchase_order_id LEFT JOIN " . DB_PREFIX . "supplier s ON s.supplier_id = po.supplier_id LEFT JOIN product_description pd ON pd.product_id = pop.product_id AND pd.language_id = '" . $this->config->get('config_language_id') . "' WHERE quantity_outstanding > 0";

		if ($data) {
			if (isset($data['filter_order_ref']) && !is_null($data['filter_order_ref'])) {
				$sql .= " AND LCASE(po.order_ref) LIKE '%" . $this->db->escape(strtolower($data['filter_order_ref'])) . "%'";
			}

			if (isset($data['filter_supplier']) && !is_null($data['filter_supplier'])) {
				$sql .= " AND po.supplier_id = '" . (int) $data['filter_supplier'] . "'";
			}

			if (isset($data['filter_product_name']) && !is_null($data['filter_product_name'])) {
				$sql .= " AND LCASE(pd.name) LIKE '%" . $this->db->escape(strtolower($data['filter_product_name'])) . "%'";
			}

			if (isset($data['filter_product_id']) && !is_null($data['filter_product_id'])) {
				$sql .= " AND pop.product_id = '" . (int) $data['filter_product_id'] . "'";
			}
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getPurchaseOrderProduct($purchase_order_product_id) {
		$sql = "SELECT * FROM " . DB_PREFIX . "purchase_order_product WHERE purchase_order_product_id = '" . (int) $purchase_order_product_id . "'";
		$query = $this->db->query($sql);
		return $query->row;
	}

	public function deductPurchaseOrderProductOutstandingQuantity($purchase_order_product_id, $quantity) {
		$sql = "UPDATE " . DB_PREFIX . "purchase_order_product SET quantity_outstanding = quantity_outstanding - '" . $quantity . "' WHERE purchase_order_product_id = '" . (int) $purchase_order_product_id . "'";
		$query = $this->db->query($sql);
	}

}

?>
