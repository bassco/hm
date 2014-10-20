<?php

class ModelCatalogSupplier extends Model {

    public function getSuppliers() {
	$sql = "SELECT supplier_id, name FROM " . DB_PREFIX . "supplier ORDER BY name ASC";
	$query = $this->db->query($sql);
	return $query->rows;
    }

    public function getSupplier($supplier_id) {
	$query = $this->db->query("SELECT supplier_id, name FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int) $supplier_id . "'");
	return $query->row;
    }

    public function addSupplier($data) {
	$this->db->query("INSERT INTO " . DB_PREFIX . "supplier SET name = '" . $this->db->escape($data['name']) . "'");
    }

    public function editSupplier($supplier_id, $data) {
	$this->db->query("UPDATE " . DB_PREFIX . "supplier SET name = '" . $this->db->escape($data['name']) . "' WHERE supplier_id = '" . (int) $supplier_id . "'");
    }

    public function deleteSupplier($supplier_id) {
		$result = $this->db->query("SELECT COUNT(*) AS counter FROM " . DB_PREFIX . "purchase_order WHERE supplier_id = '" . (int) $supplier_id . "'");
		if ($result->row['counter'] > 0) {
			return false;
		} else{
			$this->db->query("DELETE FROM " . DB_PREFIX . "supplier WHERE supplier_id = '" . (int) $supplier_id . "'");
			return true;
		}	
    }

}

?>
