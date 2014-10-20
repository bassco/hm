<?php

/*
  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

  ---------------------------------------------------------------------

  Siven (Sites n Stores) siven@sitesnstores.com.au
  OpenCart 1.5.1.3 Fastway Shipping Module 1.0

 */

class ControllerShippingFastway extends Controller {

    private $error = array();

    public function index() {
        $this->load->language('shipping/fastway');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('fastway', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');
        $this->data['text_none'] = $this->language->get('text_none');
        $this->data['text_select'] = $this->language->get('text_select');

        $this->data['entry_api_key'] = $this->language->get('entry_api_key');
        $this->data['entry_parcel'] = $this->language->get('entry_parcel');
        $this->data['entry_satchel'] = $this->language->get('entry_satchel');
        $this->data['entry_rf'] = $this->language->get('entry_rf');
        $this->data['entry_handling'] = $this->language->get('entry_handling');
        $this->data['entry_estimate'] = $this->language->get('entry_estimate');
        $this->data['entry_stripgst'] = $this->language->get('entry_stripgst');
        $this->data['entry_tax'] = $this->language->get('entry_tax');
        $this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_flat_fee'] = $this->language->get('entry_flat_fee');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['rf'])) {
            $this->data['error_rf'] = $this->error['rf'];
        } else {
            $this->data['error_rf'] = '';
        }
        
        if (isset($this->error['api_key'])) {
            $this->data['error_api_key'] = $this->error['api_key'];
        } else {
            $this->data['error_api_key'] = '';
        }

        $this->data['breadcrumbs'] = array();

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('text_shipping'),
            'separator' => ' :: '
        );

        $this->data['breadcrumbs'][] = array(
            'href' => $this->url->link('shipping/fastway', 'token=' . $this->session->data['token'], 'SSL'),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        $this->data['action'] = $this->url->link('shipping/fastway', 'token=' . $this->session->data['token'], 'SSL');

        $this->data['cancel'] = $this->url->link('extension/shipping', 'token=' . $this->session->data['token'], 'SSL');

        if (isset($this->request->post['fastway_api_key'])) {
            $this->data['fastway_api_key'] = $this->request->post['fastway_api_key'];
        } else {
            $this->data['fastway_api_key'] = $this->config->get('fastway_api_key');
        }

        if (isset($this->request->post['fastway_parcel'])) {
            $this->data['fastway_parcel'] = $this->request->post['fastway_parcel'];
        } else {
            $this->data['fastway_parcel'] = $this->config->get('fastway_parcel');
        }

        if (isset($this->request->post['fastway_satchel'])) {
            $this->data['fastway_satchel'] = $this->request->post['fastway_satchel'];
        } else {
            $this->data['fastway_satchel'] = $this->config->get('fastway_satchel');
        }

        if (isset($this->request->post['fastway_rf'])) {
            $this->data['fastway_rf'] = $this->request->post['fastway_rf'];
        } else {
            $this->data['fastway_rf'] = $this->config->get('fastway_rf');
        } 

        $this->data['rfs'] = $this->getListRFs($this->data['fastway_api_key']);

        if (isset($this->request->post['fastway_handling'])) {
            $this->data['fastway_handling'] = $this->request->post['fastway_handling'];
        } else {
            $this->data['fastway_handling'] = $this->config->get('fastway_handling');
        }

        if (isset($this->request->post['fastway_estimate'])) {
            $this->data['fastway_estimate'] = $this->request->post['fastway_estimate'];
        } else {
            $this->data['fastway_estimate'] = $this->config->get('fastway_estimate');
        }

        if (isset($this->request->post['fastway_stripgst'])) {
            $this->data['fastway_stripgst'] = $this->request->post['fastway_stripgst'];
        } else {
            $this->data['fastway_stripgst'] = $this->config->get('fastway_stripgst');
        }

        if (isset($this->request->post['fastway_tax_class_id'])) {
            $this->data['fastway_tax_class_id'] = $this->request->post['fastway_tax_class_id'];
        } else {
            $this->data['fastway_tax_class_id'] = $this->config->get('fastway_tax_class_id');
        }

        if (isset($this->request->post['fastway_geo_zone_id'])) {
            $this->data['fastway_geo_zone_id'] = $this->request->post['fastway_geo_zone_id'];
        } else {
            $this->data['fastway_geo_zone_id'] = $this->config->get('fastway_geo_zone_id');
        }

        if (isset($this->request->post['fastway_status'])) {
            $this->data['fastway_status'] = $this->request->post['fastway_status'];
        } else {
            $this->data['fastway_status'] = $this->config->get('fastway_status');
        }

        if (isset($this->request->post['fastway_sort_order'])) {
            $this->data['fastway_sort_order'] = $this->request->post['fastway_sort_order'];
        } else {
            $this->data['fastway_sort_order'] = $this->config->get('fastway_sort_order');
        }

        if (isset($this->request->post['fastway_flat_fee'])) {
            $this->data['fastway_flat_fee'] = $this->request->post['fastway_flat_fee'];
        } else {
            $this->data['fastway_flat_fee'] = $this->config->get('fastway_flat_fee');
        }

        $this->load->model('localisation/tax_class');

        $this->data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

        $this->load->model('localisation/geo_zone');

        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        $this->template = 'shipping/fastway.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'shipping/fastway')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (trim($this->request->post['fastway_api_key']) == '') {
            $this->error['api_key'] = $this->language->get('error_api_key');
        }

        if ($this->request->post['fastway_rf'] == '') {
            $this->error['rf'] = $this->language->get('error_rf');
        }

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

	private function getListRFs($api_key) {
    $rfs = array();

    $config_country_id = $this->config->get('config_country_id');

    $country_code = 1;
    switch ($config_country_id) {
        case 153: $country_code = 6;
            break; // New Zealand
        case 103: $country_code = 11;
            break; // Ireland
        case 81: $country_code = 12;
            break; // Germany
        case 193: $country_code = 24;
            break; // South Africa
        default : $country_code = 1; // defaults to Australia
    }
    
        $ch = curl_init();

        $request_url = 'http://farmapi.fastway.org/v2/psc/listrfs/' . $country_code . '?api_key=0eefc3afff8701191866c6e42d5f44d5';

        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $get_rfs = curl_exec($ch);

        curl_close($ch);
  
    $rfs = json_decode($get_rfs, true);

    return $rfs['result'];
  }

}

?>
