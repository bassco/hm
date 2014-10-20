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
  Sanjoy (Sites n Stores) sanjoy@sitesnstores.com.au
  OpenCart 1.5.1.3 Fastway Shipping Module 1.0
 
  No weight limit as advised by Tim on 28/08/2012 16:10

 */

class ModelShippingFastway extends Model {

    public function getQuote($address) {
        $this->load->language('shipping/fastway');

        if ($this->config->get('fastway_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int) $this->config->get('fastway_geo_zone_id') . "' AND country_id = '" . (int) $address['country_id'] . "' AND (zone_id = '" . (int) $address['zone_id'] . "' OR zone_id = '0')");

            if (!$this->config->get('fastway_geo_zone_id')) {
                $status = TRUE;
            } elseif ($query->num_rows) {
                $status = TRUE;
            } else {
                $status = FALSE;
            }
        } else {
            $status = FALSE;
        }


        if ($status && ($this->config->get('fastway_parcel') || $this->config->get('fastway_satchel') )) {

            //Query to find id of kilograms (g) as 1.5.1.1 removed the availability of the named unit
            $unit_query = $this->db->query("SELECT weight_class_id FROM " . DB_PREFIX . "weight_class_description where LOWER(unit) = 'kg'");

            if ($unit_query->num_rows) {
                $unit_kg = $unit_query->row['weight_class_id'];
            }

            $weight = $this->weight->convert($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $unit_kg);

            $this->load->model('localisation/country');

            $country_info = $this->model_localisation_country->getCountry($address['country_id']);

            //Initialise variables	
            $method_data = array();
            $quote_data = array();
            $error = FALSE;

            //These errors will clobber each other, so only one error will be displayed at a time	
            if ($weight == 0) {
                $error = 'The basket weight is 0g, unable to calculate shipping costs';
            }
            
            $validmethods = array("parcel", "satchel");

            //Calculate the cube root of the item volume to send to the fastway module, if it's a single item.. the actual dimensions will be sent
            if (@count($validmethods) > 0) {

                //Query to find out if mm are configured in the database because OpenCart developers thought it wasn't needed in the API (currently no error condition if it doesn't exist) 
                $unit_query = $this->db->query("SELECT length_class_id FROM " . DB_PREFIX . "length_class_description where LOWER(unit) = 'cm'");

                if ($unit_query->num_rows) {
                    $unit_cm = $unit_query->row['length_class_id'];
                }

                //Set the total cubed amount to 0
                $cartcubed = 0;

                foreach ($this->cart->getProducts() as $cartitem) {
                    //Check the length class, if it isn't mm we need to convert it

                    if ($cartitem['length_class_id'] != $unit_cm) {
                        if ($cartitem['width'] != 0) {
                            $cartitem['width'] = $this->length->convert($cartitem['width'], $cartitem['length_class_id'], $unit_cm);
                        }

                        if ($cartitem['height'] != 0) {
                            $cartitem['height'] = $this->length->convert($cartitem['height'], $cartitem['length_class_id'], $unit_cm);
                        }

                        if ($cartitem['length'] != 0) {
                            $cartitem['length'] = $this->length->convert($cartitem['length'], $cartitem['length_class_id'], $unit_cm);
                        }
                    }

                    //Combine the total cubed capacity (value will be unused if no items in the cart)
                    $cartcubed += ($cartitem['width'] * $cartitem['height'] * $cartitem['length'] * $cartitem['quantity']);
                }

                //If it's a single item send the real dimensions, if not send the cubed root
                if ($this->cart->countProducts() == 1) {
                    $fastway_width = intval($cartitem['width']);
                    $fastway_height = intval($cartitem['height']);
                    $fastway_length = intval($cartitem['length']);
                } else if ($this->cart->countProducts() > 1) {
                    $fastway_width = round(pow($cartcubed, 1 / 3));
                    $fastway_height = round(pow($cartcubed, 1 / 3));
                    $fastway_length = round(pow($cartcubed, 1 / 3));
                }
            }

            $quotes = $this->getFastwayQuote($address, $weight, $fastway_width, $fastway_height, $fastway_length);
            
            if (!isset($quotes['error'])) {
                foreach ($quotes as $quote) {
                    if (isset($quote['totalprice_normal'])){
                    if ($quote['totalprice_normal'] > 0) {
                        $postmethod = strtolower($quote['type']);
                        
						if ($this->cart->getTotal() >= (float)$this->config->get('free_total')) { $quote['cost'] = 0.00;}

                        $quote_data['fastway_' . $postmethod] = array(
                            'code' => 'fastway.fastway_' . $postmethod,
                            'title' => $quote['description'],
                            'cost' => $quote['cost'],
                            'tax_class_id' => $this->config->get('fastway_tax_class_id'),
							'text' => $this->currency->format(sprintf('%.2f', ($this->tax->calculate($quote['cost'], $this->config->get('fastway_tax_class_id')))), $this->config->get('config_currency'))
                        );
              //              'text' => $this->currency->format(sprintf('%.2f', ($this->tax->calculate($quote['cost'], $this->config->get('fastway_tax_class_id'), $this->config->get('config_tax')))), $this->config->get('config_currency'))
              //          );
                    }
                    } else {
                    $error="Unable to contact Fastway";
                    }
                }
            } else {
                $error = $quotes['error'];
            }

            $method_data = array(
                'code' => 'fastway',
                'title' => $this->language->get('text_title'),
                'quote' => $quote_data,
                'sort_order' => $this->config->get('fastway_sort_order'),
                'error' => $error
            );

            return $method_data;
        } //End fastway module is enabled
    }

//End of getQuote function

    private function getFastwayQuote($address, $weight, $width, $height, $length) {
        $no_of_boxes = 1;
        $weight_additional = 0;
        $cubic_weight = (($width/100) * ($height/100) * ($length/100)) * 250;
        if ($weight > 25 || ($cubic_weight > 25)) {
            if ($cubic_weight > $weight) {
                $weight = $cubic_weight;
            }
            $no_of_boxes = ceil($weight / 25);
            $weight_additional = $weight - (25 * ($no_of_boxes - 1));
            $weight = 25;
        }
        
        $ch = curl_init();
        
        if ($no_of_boxes > 1) {
            $request_url = 'http://farmapi.fastway.org/v2/psc/lookup/' . $this->config->get('fastway_rf') . '/' . str_replace (' ', '%20', $address['city']) . '/' . trim($address['postcode']) . '?WeightInKg=' . $weight . '&api_key=' . $this->config->get('fastway_api_key');
        } else {
            $request_url = 'http://farmapi.fastway.org/v2/psc/lookup/' . $this->config->get('fastway_rf') . '/' . str_replace (' ', '%20', $address['city']) . '/' . trim($address['postcode']) . '?LengthInCm=' . $length . '&WidthInCm=' . $width . '&HeightInCm=' . $height . '&WeightInKg=' . $weight . '&api_key=' . $this->config->get('fastway_api_key');
        }

        curl_setopt($ch, CURLOPT_URL, $request_url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $get_quote = curl_exec($ch);
        
        curl_close($ch);
                
        $quotes = json_decode($get_quote, true);
        
        $services_additional = array();
        if ($no_of_boxes > 1 && ($weight_additional > 0.0)) {
            $request_url = 'http://farmapi.fastway.org/v2/psc/lookup/' . $this->config->get('fastway_rf') . '/' . str_replace (' ', '%20', $address['city']) . '/' . trim($address['postcode']) . '?WeightInKg=' . $weight_additional . '&api_key=' . $this->config->get('fastway_api_key');
            
            $ch = curl_init();
            
            curl_setopt($ch, CURLOPT_URL, $request_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $get_quote_additional = curl_exec($ch);
            
            curl_close($ch);
           
            $quotes_additional = json_decode($get_quote_additional, true);
            
            if (!isset($quotes['error']) && (count($quotes['result']['services']))) {
                $services_additional = $quotes_additional['result']['services'];
            }
        }

        if (isset($quotes['error'])) {
            if (substr($quotes['error'], 0, 32) == 'No services available for suburb') {
                $fastway_quote['error'] = $this->language->get('text_error_mismatch');
            } else {
                $fastway_quote['error'] = $quotes['error'];
            }
        } else {

            if (!count($quotes['result']['services'])) {
                $fastway_quote[0] = -1;
                $fastway_quote[1] = 'Error interfacing with Fastway Courier (charge)';
            } else {
                $services = $quotes['result']['services'];

                $fastway_quote = array();

                foreach ($services as $service) {
                    if ($this->config->get('fastway_' . strtolower($service['type']))) {
                        $service['cost'] = (float) ($this->config->get('fastway_stripgst') ? $service['totalprice_normal_exgst'] : $service['totalprice_normal']) + (float) $this->config->get('fastway_handling');
                        
                        $service['description'] = 'Flat Rate Subsidised Fee';//$this->language->get('text_' . strtolower($service['type'])) . ': ' . $service['name'] . ' [' .$service['labelcolour_pretty'] . ']';
                        
                        if ($no_of_boxes > 1) {                            
                            if ($weight_additional > 0.0) {
                                $service['cost'] *= $no_of_boxes - 1;
                                foreach ($services_additional as $service_addional) {
                                    if ($service_addional['type'] == $service['type']) {
                                        $service_addional['cost'] = (float) ($this->config->get('fastway_stripgst') ? $service['totalprice_normal_exgst'] : $service['totalprice_normal']) + (float) $this->config->get('fastway_handling');

                                        $service['cost'] += $service_addional['cost'];
                                    }
                                }
                                
                                $service['description'] .= ' x ' . $no_of_boxes . ' boxes';
                            } else {
                                $service['cost'] *= $no_of_boxes;
                                
                                
                            }                            
                            
                        }
                        
                        // FLAT FEE
			$service['cost']=(float)$this->config->get('fastway_flat_fee');
                                
                        if ($this->config->get('fastway_estimate') && isset($quotes['result']['delivery_timeframe_days'])) {
                            $service['description'].= ' (est. ' . $quotes['result']['delivery_timeframe_days'] . ' day delivery)';
                        }
                    $fastway_quote[] = $service;
                    }
                }
            }
        }

        return $fastway_quote;
    }

}

?>
