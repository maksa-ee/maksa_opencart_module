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
 */
class ControllerPaymentMaksaEE extends Controller
{
    private $error = array();

    public function index()
    {
        $this->load->language('payment/maksa_ee');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->load->model('setting/setting');

            $this->model_setting_setting->editSetting('maksa_ee', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled']   = $this->language->get('text_enabled');
        $this->data['text_disabled']  = $this->language->get('text_disabled');
        $this->data['text_all_zones'] = $this->language->get('text_all_zones');

        $this->data['entry_test_mode']   = $this->language->get('entry_test_mode');
        $this->data['entry_client_id']   = $this->language->get('entry_client_id');
        $this->data['entry_public_key']  = $this->language->get('entry_public_key');
        $this->data['entry_private_key'] = $this->language->get('entry_private_key');

        $this->data['entry_total']            = $this->language->get('entry_total');
        $this->data['entry_completed_status'] = $this->language->get('entry_completed_status');
        $this->data['entry_failed_status']    = $this->language->get('entry_failed_status');

        $this->data['entry_geo_zone']   = $this->language->get('entry_geo_zone');
        $this->data['entry_status']     = $this->language->get('entry_status');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save']   = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        # ERRORS
        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        }
        else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['client_id'])) {
            $this->data['error_client_id'] = $this->error['client_id'];
        }
        else {
            $this->data['error_client_id'] = '';
        }

        if (isset($this->error['public_key'])) {
            $this->data['error_public_key'] = $this->error['public_key'];
        }
        else {
            $this->data['error_public_key'] = '';
        }

        if (isset($this->error['private_key'])) {
            $this->data['error_private_key'] = $this->error['private_key'];
        }
        else {
            $this->data['error_private_key'] = '';
        }

        # BREADCRUMBS
        $this->data['breadcrumbs'] = array();
        $this->data['breadcrumbs'][] = array(
            'href'       => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_home'),
            'separator' => false
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('text_payment'),
            'separator' => ' :: '
        );
        $this->data['breadcrumbs'][] = array(
            'href'      => $this->url->link('payment/maksa_ee', 'token=' . $this->session->data['token'], 'SSL'),
            'text'      => $this->language->get('heading_title'),
            'separator' => ' :: '
        );

        # ACTIONS
        $this->data['action'] = $this->url->link('payment/maksa_ee', 'token=' . $this->session->data['token'], 'SSL');
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

        # VALUES
        if (isset($this->request->post['maksa_ee_test_mode'])) {
            $this->data['maksa_ee_test_mode'] = $this->request->post['maksa_ee_test_mode'];
        }
        else {
            $this->data['maksa_ee_test_mode'] = $this->config->get('maksa_ee_test_mode');
        }

        if (isset($this->request->post['maksa_ee_client_id'])) {
            $this->data['maksa_ee_client_id'] = $this->request->post['maksa_ee_client_id'];
        }
        else {
            $this->data['maksa_ee_client_id'] = $this->config->get('maksa_ee_client_id');
        }

        if (isset($this->request->post['maksa_ee_public_key'])) {
            $this->data['maksa_ee_public_key'] = $this->request->post['maksa_ee_public_key'];
        }
        else {
            $this->data['maksa_ee_public_key'] = $this->config->get('maksa_ee_public_key');
        }

        if (isset($this->request->post['maksa_ee_private_key'])) {
            $this->data['maksa_ee_private_key'] = $this->request->post['maksa_ee_private_key'];
        }
        else {
            $this->data['maksa_ee_private_key'] = $this->config->get('maksa_ee_private_key');
        }

        if (isset($this->request->post['maksa_ee_total'])) {
            $this->data['maksa_ee_total'] = $this->request->post['maksa_ee_total'];
        }
        else {
            $this->data['maksa_ee_total'] = $this->config->get('maksa_ee_total');
        }

        $this->load->model('localisation/order_status');
        $this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
        if (isset($this->request->post['maksa_ee_completed_status_id'])) {
            $this->data['maksa_ee_completed_status_id'] = $this->request->post['maksa_ee_completed_status_id'];
        }
        else {
            $this->data['maksa_ee_completed_status_id'] = $this->config->get('maksa_ee_completed_status_id') ?: 5;
        }

        if (isset($this->request->post['maksa_ee_failed_status_id'])) {
            $this->data['maksa_ee_failed_status_id'] = $this->request->post['maksa_ee_failed_status_id'];
        }
        else {
            $this->data['maksa_ee_failed_status_id'] = $this->config->get('maksa_ee_failed_status_id') ?: 10;
        }

        if (isset($this->request->post['maksa_ee_geo_zone_id'])) {
            $this->data['maksa_ee_geo_zone_id'] = $this->request->post['maksa_ee_geo_zone_id'];
        }
        else {
            $this->data['maksa_ee_geo_zone_id'] = $this->config->get('maksa_ee_geo_zone_id');
        }
        $this->load->model('localisation/geo_zone');
        $this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

        if (isset($this->request->post['maksa_ee_status'])) {
            $this->data['maksa_ee_status'] = $this->request->post['maksa_ee_status'];
        }
        else {
            $this->data['maksa_ee_status'] = $this->config->get('maksa_ee_status');
        }

        if (isset($this->request->post['maksa_ee_sort_order'])) {
            $this->data['maksa_ee_sort_order'] = $this->request->post['maksa_ee_sort_order'];
        }
        else {
            $this->data['maksa_ee_sort_order'] = $this->config->get('maksa_ee_sort_order');
        }

        $this->template = 'payment/maksa_ee.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    private function validate()
    {
        if (!$this->user->hasPermission('modify', 'payment/maksa_ee')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['maksa_ee_client_id']) {
            $this->error['client_id'] = $this->language->get('error_client_id');
        }

        if (!$this->request->post['maksa_ee_public_key']) {
            $this->error['public_key'] = $this->language->get('error_public_key');
        }

        if (!$this->request->post['maksa_ee_private_key']) {
            $this->error['private_key'] = $this->language->get('error_private_key');
        }

        if (!$this->error) {
            return true;
        }
        else {
            return false;
        }
    }
}
