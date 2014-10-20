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
	 * Sets the newsletter flag on a customer account. 
	 *
	 * @param string Email address to update
	 * @param boolean Set the newsletter option to this 0, 1
	 */
	public function set_newsletter($email, $flag) 
	{
		if (!empty($email) && is_bool($flag)) {

			$flag = ($flag) ? 1 : 0;
			
			$query = $this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$flag . "' WHERE email = '" . $this->db->escape($email) . "'");
		}
	}
}
?>

