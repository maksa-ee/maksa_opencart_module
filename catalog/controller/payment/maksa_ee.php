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
    protected $ulinkService = null;

    public function link($link, $args = '')
    {
        if ($this->url) {
            if (is_callable(array($this->url, 'link'))) {
                return $this->url->link($link) . $args;
            } else {
                return $this->url->https($link) . $args;
            }
        } else {
            return HTTPS_SERVER . 'index.php?route=' . $link . $args;
        }
    }


    protected function getUlinkService()
    {
        if (null == $this->ulinkService) {
            include_once(DIR_SYSTEM . 'maksa_ee/UlinkService.php');

            $callbackUrl = $this->link('payment/maksa_ee/callback');
            $confirmUrl = $this->link('payment/maksa_ee/confirmation') . '&order_id=' . $this->session->data['order_id'];

            $this->ulinkService = new UlinkService(
                $this->config->get('maksa_ee_client_id'),
                $this->config->get('maksa_ee_public_key'),
                $this->config->get('maksa_ee_private_key'),
                'EUR',
                $confirmUrl,
                $callbackUrl
            );
        }

        return $this->ulinkService;
    }

    protected function index()
    {
        $this->language->load('payment/maksa_ee');

        $this->data['button_confirm'] = $this->language->get('button_confirm');

        if (!$this->config->get('maksa_ee_test_mode')) {
            $this->data['action'] = 'https://maksa.ee/pay/prod';
        }
        else {
            $this->data['action'] = 'https://maksa.ee/pay/test';
        }

        $this->load->model('checkout/order');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        if ($order_info) {
            $currencies = array(
                'EUR',
            );

            $currency_code = isset($order_info['currency_code']) ? $order_info['currency_code'] : $order_info['currency'];

            if (in_array($currency_code, $currencies)) {
                $currency = $currency_code;
            }
            else {
                $currency = 'EUR';
            }

            $order = array();
            foreach ($this->cart->getProducts() as $product) {
                $item = array(
                    'name'         => $product['name'],
                    'description'  => $product['model'],
                    'oneItemPrice' => (string) $this->currency->format($product['price'], $currency, false, false),
                    'quantity'     => $product['quantity'],
                );
                $order[] = $item;
            }

            $shipping = $this->currency->format($order_info['total'] - $this->cart->getSubTotal(), $currency, false, false);
            if ($shipping > 0) {
                $order[] = array(
                    'name'         => $this->language->get('text_total'),
                    'description'  => '',
                    'oneItemPrice' => (string) $shipping,
                    'quantity'     => 1,
                );
            }

            $amount = $this->currency->format($order_info['total'], $currency, false, false);

            $ulinkService = $this->getUlinkService();
            $signedRequest = $ulinkService->encrypt(
                array(
                    'clientTransactionId' => $this->session->data['order_id'],
                    'amount'              => (string) $amount,
                    'order'               => $order
                )
            );
            $this->data['signedRequest'] = $signedRequest;

            $this->id = 'payment';

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/maksa_ee.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/maksa_ee.tpl';
            }
            else {
                $this->template = 'default/template/payment/maksa_ee.tpl';
            }

            $this->render();
        }
    }

    public function wait()
    {
        $order_id = 0;
        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        }

        $this->load->model('account/order');
        $order_info = $this->model_account_order->getOrder($order_id);

        $response = 'waiting';
        if ($order_info) {
            if ($order_info['order_status_id']) {
                if ($this->config->get('maksa_ee_completed_status_id') == $order_info['order_status_id']) {
                    $response = 'ok';
                }
                else if ($this->config->get('maksa_ee_failed_status_id') == $order_info['order_status_id']) {
                    $response = 'not_ok';
                }
            }
        }

        echo $response;
    }

    public function confirmation()
    {

        $order_id = 0;
        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        }

        $this->language->load('payment/maksa_ee');

        $this->data['text_please_wait']     = $this->language->get('text_please_wait');
        $this->data['text_payment_success'] = $this->language->get('text_payment_success');
        $this->data['text_payment_failure'] = $this->language->get('text_payment_failure');
        $this->data['text_go_to_order']     = $this->language->get('text_go_to_order');

        $this->data['wait_link']    = $this->link('payment/maksa_ee/wait') . '&order_id=' . $order_id;
        $this->data['order_link']   = $this->link('account/invoice') . '&order_id=' . $order_id;
        $this->data['success_link'] = $this->link('checkout/success');

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/maksa_ee_confirmation.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/maksa_ee_confirmation.tpl';
        }
        else {
            $this->template = 'default/template/payment/maksa_ee_confirmation.tpl';
        }
        $this->children = array(
//            'common/column_left',
//            'common/column_right',
//            'common/content_top',
//            'common/content_bottom',
            'common/footer',
            'common/header'
        );

        $this->response->setOutput($this->render(true));
    }
    
    public function callback()
    {
        $response = array('status' => 'NOTOK');
        if (isset($this->request->post['signedResponse'])) {
            $rawData = $this->request->post['signedResponse'];
        }
        else {
            $rawData = null;
        }

        if ($rawData) {
            $ulinkService = $this->getUlinkService();

            try {
                $responseData = $ulinkService->decrypt($rawData);

                $testPayment = true;
                if (isset($responseData['isTest']) && false === $responseData['isTest']) {
                    // normal payment
                    $testPayment = false;
                }
                $response['isTest'] = $testPayment;

                $order_id = (int) $responseData['clientTransactionId'];

                $this->load->model('checkout/order');

                $order_info = $this->model_checkout_order->getOrder($order_id);

                if ($order_info) {

                    // payment success
                    if ($responseData['success']) {
                        $this->log->write('MAKSA_EE :: ' . $testPayment ? 'Test OK' : 'Payment OK');
                        $order_status_id = $this->config->get('maksa_ee_completed_status_id');

                        $response['msg'] = 'Payment success.';
                    }

                    // payment failure
                    else {
                        $this->log->write('MAKSA_EE :: ' . $testPayment ? 'Test Failure' : 'Payment Failure ' . ': errors = { ' . implode(', ', $responseData['errors']) . ' }');
                        $order_status_id = $this->config->get('maksa_ee_failed_status_id');

                        $response['msg'] = 'Payment failure.';
                    }

                    if (!$order_info['order_status_id']) {
                        $this->model_checkout_order->confirm($order_id, $order_status_id);
                    }
                    else {
                        $this->model_checkout_order->update($order_id, $order_status_id);
                    }

                    $response['order_id'] = $order_id;
                    $response['status']   = 'OK';
                }
            } catch (UlinkException $e) {
                // error
                $response['msg'] = $e->getMessage();
            }
        }

        echo json_encode($response);
    }
}
