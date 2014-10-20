<?php
class ModelReportWishlisted extends Model {
	
	public function getWishlisted($data = array()) {
	
		$wl = $this->db->query("SELECT wishlist FROM " . DB_PREFIX . "customer");
		$products_array = array();
		
		foreach ($wl->rows as $qaz) {
	    	$products_array[] = unserialize($qaz['wishlist']);
		}
		
		$cleaned_array = array_filter($products_array);
	
		$products2 = array();

		foreach ($cleaned_array as $row) {
		    foreach ($row as $result) {
		        $products2[] = $result;
	    	} 
		} 

		$unq = array_unique($products2);
		return $unq;
	}
	
	public function getTotalWishlisted() {
	
		$wl = $this->db->query("SELECT wishlist FROM " . DB_PREFIX . "customer");
		$products_array = array();
		
		foreach ($wl->rows as $qaz) {
	    	$products_array[] = unserialize($qaz['wishlist']);
		}
	
		$cleaned_array = array_filter($products_array);
	
		$products2 = array();

		foreach ($cleaned_array as $row) {
		    foreach ($row as $result) {
		        $products2[] = $result;
	    	} 
		} 
	
		$unq = array_unique($products2);
		$count = count($unq);
		return $count;
	}
	
	public function getWishlistCount($product_id) {
	
		$wl = $this->db->query("SELECT wishlist FROM " . DB_PREFIX . "customer");
		$products_array = array();
		
		foreach ($wl->rows as $qaz) {
	    	$products_array[] = unserialize($qaz['wishlist']);
		}
	
		$cleaned_array = array_filter($products_array);
	
		$products2 = array();

		foreach ($cleaned_array as $row) {
		    foreach ($row as $result) {
		        $products2[] = $result;
	    	} 
		} 
	
		$counts = array_count_values($products2);
		$number = $counts[$product_id];
		return $number;
	}
	public function getCustomerWishlisted($item) {
	
		$wl = $this->db->query("SELECT customer_id, wishlist FROM " . DB_PREFIX . "customer");

		$products_array = array();

		foreach ($wl->rows as $v) {
	    	$products_array[] = array(
			  'wishlist' => (empty($v['wishlist']) ? array() : unserialize($v['wishlist'])), 
			  'customer' => $v['customer_id']
			);
		}
		
		$customer_ids = array();
		
		foreach($products_array as $v) {
    	  if(in_array($item, $v['wishlist']))
            $customer_ids[] = $v['customer'];
		}

		return $customer_ids;
	}
	
	public function getwlCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
	
		return $query->row;
	}
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT p.product_id, pd.name FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) WHERE p.product_id = '" . (int)$product_id . "'");
				
		return $query->row;
	}
		
	public function getCustomerTotal($item) {
	
		$wl = $wl = $this->db->query("SELECT customer_id, wishlist FROM " . DB_PREFIX . "customer");

		$products_array = array();

		foreach ($wl->rows as $v) {
	    	$products_array[] = array(
			  'wishlist' => (empty($v['wishlist']) ? array() : unserialize($v['wishlist'])), 
			  'customer' => $v['customer_id']
			);
		}
		
		$customer_ids = array();
		
		foreach($products_array as $v) {
    	  if(in_array($item, $v['wishlist']))
          $customer_ids[] = $v['customer'];
		}

		$unq = array_unique($customer_ids);
		$count = count($unq);
		return $count;

		return $customer_ids;
	}
	
	public function getCustWishlist($customer_id) {
	
		$wl = $this->db->query("SELECT wishlist FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$products_array = array();
		
		foreach ($wl->rows as $qaz) {
	    	$products_array[] = unserialize($qaz['wishlist']);
		}
		
		$cleaned_array = array_filter($products_array);
	
		$products2 = array();

		foreach ($cleaned_array as $row) {
		    foreach ($row as $result) {
		        $products2[] = $result;
	    	} 
		} 
		$unq = array_unique($products2);
		return $unq;
	}
	
	public function getWishlistedTotal($customer_id) {
	
		$wl = $this->db->query("SELECT wishlist FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$products_array = array();
		
		foreach ($wl->rows as $qaz) {
	    	$products_array[] = unserialize($qaz['wishlist']);
		}
	
		$cleaned_array = array_filter($products_array);
	
		$products2 = array();

		foreach ($cleaned_array as $row) {
		    foreach ($row as $result) {
		        $products2[] = $result;
	    	} 
		} 
	
		$unq = array_unique($products2);
		$count = count($unq);
		return $count;
	}
	
	public function getWishlistCustomers($data = array(), $customer_id) {
		$sql = "SELECT *, CONCAT(c.firstname, ' ', c.lastname) AS name, cgd.name AS customer_group FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "customer_group_description cgd ON (c.customer_group_id = cgd.customer_group_id) WHERE c.customer_id = '" . $customer_id . "' and cgd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

		$implode = array();
		
		if (!empty($data['filter_name'])) {
			$implode[] = "LCASE(CONCAT(c.firstname, ' ', c.lastname)) LIKE '%" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "%'";
		}
		
		if (!empty($data['filter_email'])) {
			$implode[] = "LCASE(c.email) LIKE '" . $this->db->escape(utf8_strtolower($data['filter_email'])) . "%'";
		}
		
		if (!empty($data['filter_customer_group_id'])) {
			$implode[] = "c.customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}	
			
		if (!empty($data['filter_ip'])) {
			$implode[] = "c.customer_id IN (SELECT customer_id FROM " . DB_PREFIX . "customer_ip WHERE ip = '" . $this->db->escape($data['filter_ip']) . "')";
		}	
				
		if (!empty($data['filter_date_added'])) {
			$implode[] = "DATE(c.date_added) = DATE('" . $this->db->escape($data['filter_date_added']) . "')";
		}
		
		if ($implode) {
			$sql .= " AND " . implode(" AND ", $implode);
		}
				
		$query = $this->db->query($sql);
		
		return $query->rows;	
	}
	
	public function getCustomersWithWishlist() {
	
		$wl = $this->db->query("SELECT customer_id, wishlist FROM " . DB_PREFIX . "customer");

		$products_array = array();

		foreach ($wl->rows as $v) {
	    	$products_array[] = array(
			  'wishlist' => (empty($v['wishlist']) ? array() : unserialize($v['wishlist'])), 
			  'customer' => $v['customer_id']
			);
		}
		
		$customer_ids = array();
		
		foreach($products_array as $v) {
			if(empty($v['wishlist']) === false && isset($v['wishlist']) === true) {
            $customer_ids[] = $v['customer'];
			}
		}
		
		return $customer_ids;
	}
	
	public function getTotalCustomersWithWishlist() {
	
		$wl = $this->db->query("SELECT customer_id, wishlist FROM " . DB_PREFIX . "customer");

		$products_array = array();

		foreach ($wl->rows as $v) {
	    	$products_array[] = array(
			  'wishlist' => (empty($v['wishlist']) ? array() : unserialize($v['wishlist'])), 
			  'customer' => $v['customer_id']
			);
		}
		
		$customer_ids = array();
		
		foreach($products_array as $v) {
			if(empty($v['wishlist']) === false && isset($v['wishlist']) === true) {
            $customer_ids[] = $v['customer'];
			}
		}
		
		$unq = array_unique($customer_ids);
		$count = count($unq);
		return $count;
	}
}
?>