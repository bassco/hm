<?php
class ModelCatalogComingSoon extends Model {

function assoc_array_shuffle ($array,$limit=3) {
    while (count($array) > 0) {
        $val = array_rand($array);
        $new_arr[$val] = $array[$val];
        unset($array[$val]);
    }
    return array_splice($new_arr,0,$limit);
}

    public function getComingSoonProducts($limit) {

		$this->load->model('catalog/product');

		$product_data = $this->cache->get('product.coming_soon.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit);

		if (!$product_data) {
			$query = $this->db->query("SELECT p.product_id FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE p.status = '1' AND p.date_available > NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' ORDER BY p.date_added DESC");

			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}

			$this->cache->set('product.coming_soon.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . (int)$limit, $product_data);
		}
 		
		// Shuffle the products assoc array and return the first limit records

		return $this->assoc_array_shuffle($product_data,$limit);
	}
}
?>
