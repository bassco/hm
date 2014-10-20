<?php 
/**
 * Mailchimp OpenCart Module, connects the MailChimp 
 * Newsletter services with your OpenCart store.
 *
 * @author		Ian Gallacher (www.opencartstore.com)
 * @version		1.5.3.x
 * @support		https://opencartstorecom.zendesk.com/home
 * @email		info@opencartstore.com
 */

class ModelModuleMailchimp extends Model {
	/**
	 * Returns a count and the records of all customers who have
	 * subscribed to the newsletter option.
	 * 
	 * @param int
	 * @return array
	 */
	public function get_newsletter_subscribers($customer_group = '') 
	{
		$sql = "SELECT firstname, lastname, email FROM " . DB_PREFIX . "customer WHERE `newsletter` = 1";

		if (!empty($customer_group)) {
			$sql .= ' AND customer_group_id = ' . $customer_group;
		}

		$query = $this->db->query($sql);
		
		if (count($query->rows) > 0) {
			$data = array(
				'total' 	=> count($query->rows),
				'customers'	=> $query->rows
			);
			return $data;
		}	
		return false;
	}

	/**
	 * Returns a count and the records of all customers.
	 * 
	 * @param int
	 * @return array
	 */
	public function get_all_customers($customer_group = '') 
	{
		$sql = "SELECT firstname, lastname, email FROM " . DB_PREFIX . "customer";

		if (!empty($customer_group)) {
			$sql .= ' WHERE customer_group_id = ' . $customer_group;
		}

		$query = $this->db->query($sql);
		
		if (count($query->rows) > 0) {
			$data = array(
				'total' 	=> count($query->rows),
				'customers'	=> $query->rows
			);
			return $data;
		}	
		return false;
	}
	
	/**
	 * Returns records of all customers who are not subscribed to 
	 * the newsletter.
	 * 
	 * @param int
	 * @return array
	 */
	public function get_newsletter_unsubscribed($customer_group = '') 
	{
		$sql = "SELECT email FROM " . DB_PREFIX . "customer WHERE `newsletter` = 0";

		if (!empty($customer_group)) {
			$sql .= ' AND customer_group_id = ' . $customer_group;
		}

		$query = $this->db->query($sql);

		if (count($query->rows) > 0) {
			$data = array();

			foreach ($query->rows as $email_address) {
				$data[] = $email_address['email'];
			}

			return $data;
		}
		return false;
	}
}
?>