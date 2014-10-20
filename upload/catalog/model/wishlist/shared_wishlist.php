<?php
class ModelWishlistSharedWishlist extends Model {

	public function getWishlist($customer_id, $name) {
		
			$customer_query = $this->db->query("SELECT wishlist, firstname FROM " . DB_PREFIX . "customer WHERE customer_id = '" . $this->db->escape($customer_id) . "' and firstname = '" . $this->db->escape($name) . "'");
		
			if ($customer_query->num_rows) {
				$wishlist = unserialize($customer_query->row['wishlist']);
			} else {
				$wishlist = '';
			}
		return $wishlist;
	}
}
?>