<?php

/*
 * Copyright (c) 2012 "Cravler", http://github.com/cravler
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:

 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

/**
 * @author Cravler <http://github.com/cravler>
 * @author Alex Rudakov <ribozz@gmail.com>
 */
class ModelPaymentMaksaEE extends Model
{
    public function getMethod($param1, $param2 = null)
    {
        if (is_callable(array($this->url, 'link'))) {
            return $this->_getMethod_1_5($param1, $param2);
        }
        elseif (!$this->url) {
            return $this->_getMethod_1_4($param1);
        }
        else {
            return $this->_getMethod_1_3($param1, $param2);
        }
    }
    
    public function _getMethod_1_3($country_id, $zone_id)
    {
        $this->load->language('payment/maksa_ee');

        if ($this->config->get('maksa_ee_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('maksa_ee_geo_zone_id') . "' AND country_id = '" . (int)$country_id . "' AND (zone_id = '" . (int)$zone_id . "' OR zone_id = '0')");

            if (!$this->config->get('maksa_ee_geo_zone_id')) {
                $status = true;
            }
            elseif ($query->num_rows) {
                $status = true;
            }
            else {
                $status = false;
            }
        }
        else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'id'         => 'maksa_ee',
                'title'      => $this->language->get('text_title'),
                'sort_order' => $this->config->get('maksa_ee_sort_order')
            );
        }

        return $method_data;
    }
    
    public function _getMethod_1_4($address)
    {
        $this->load->language('payment/maksa_ee');
        
        if ($this->config->get('maksa_ee_status')) {
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('maksa_ee_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
            
            if (!$this->config->get('maksa_ee_geo_zone_id')) {
                $status = true;
            } 
            elseif ($query->num_rows) {
                $status = true;
            } 
            else {
                $status = false;
            }
        } 
        else {
            $status = false;
        }
        
        $method_data = array();
    
        if ($status) {  
            $method_data = array( 
                'id'         => 'maksa_ee',
                'title'      => $this->language->get('text_title'),
                'sort_order' => $this->config->get('maksa_ee_sort_order')
            );
        }
   
        return $method_data;
    }

    public function _getMethod_1_5($address, $total)
    {
        $this->load->language('payment/maksa_ee');

        if ($this->config->get('maksa_ee_status')) {

            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('maksa_ee_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

            if ($this->config->get('maksa_ee_total') > $total) {
                $status = false;
            }
            elseif (!$this->config->get('maksa_ee_geo_zone_id')) {
                $status = true;
            }
            elseif ($query->num_rows) {
                $status = true;
            }
            else {
                $status = false;
            }
        }else {
            $status = false;
        }

        $method_data = array();

        if ($status) {
            $method_data = array(
                'code'       => 'maksa_ee',
                'title'      => $this->language->get('text_title'),
                'sort_order' => $this->config->get('maksa_ee_sort_order'),
            );
        }

        return $method_data;
    }
}
