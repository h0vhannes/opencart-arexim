<?php

class ControllerPaymentAREXIMBANK extends Controller {
    public function index_prepare()
	{
        $this->data['button_confirm'] = $this->language->get('button_confirm');
        $this->data['button_back'] = $this->language->get('button_back');
        
        $this->language->load('payment/areximbank');

        $this->data['areximbank_referring_text'] = $this->language->get('areximbank_referring_text');
        $this->data['areximbank_our_address'] = $this->language->get('areximbank_our_address');
		$this->data['address'] = nl2br($this->config->get('config_address'));

        $this->load->model('checkout/order');
        
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        
        $this->load->library('encryption');
        
        $total_amount = (int) 100 * $order_info['total'];
        
        if ($this->config->get('areximbank_testmode') == 1)
        {
            $this->data['action'] = $this->config->get('areximbank_rooturl').'?lang='.strtoupper($order_info["language_code"]).'&merch_id='.$this->config->get('areximbank_merchant').'&back_url_s='.HTTP_SERVER.'checkout/success&back_url_f='.HTTP_SERVER.'checkout/error&o.order_id='.$order_info["order_id"].'&o.amount='.$total_amount.'&o.comment='.$this->session->data['comment'].'&o.customer_id='.$this->session->data['customer_id'].'&o.currency='.$this->session->data['currency'].'&o.firstname='.$order_info["firstname"].'&o.lastname='.$order_info["lastname"].'&o.email='.$order_info["email"];
        }
        else
        {
            $this->data['action'] = $this->config->get('areximbank_rooturl').'?lang='.strtoupper($order_info["language_code"]).'&merch_id='.$this->config->get('areximbank_merchant').'&page_id='.$this->config->get('areximbank_page_id').'&back_url_s='.HTTP_SERVER.'checkout/success&back_url_f='.HTTP_SERVER.'checkout/error&o.order_id='.$order_info["order_id"].'&o.amount='.$total_amount.'&o.comment='.$this->session->data['comment'].'&o.customer_id='.$this->session->data['customer_id'].'&o.currency='.$this->session->data['currency'].'&o.firstname='.$order_info["firstname"].'&o.lastname='.$order_info["lastname"].'&o.email='.$order_info["email"];
        }

        $this->data['areximbank_merchant'] = $this->config->get('areximbank_merchant');
        
//        substr($order_info['total'], '.', 0, strpos($order_info['total']+1);
//        $this->data['areximbank_amount'] = $this->currency->format($order_info['total'], $this->session->data['currency'],FALSE);
        
        $this->data['areximbank_amount'] = $total_amount;
        
        if ($this->session->data['currency'] == 'AMD')
            $this->data['areximbank_currency'] = "051";
        if ($this->session->data['currency'] == 'RUR')
            $this->data['areximbank_currency'] = "810";
        if ($this->session->data['currency'] == 'USD')
            $this->data['areximbank_currency'] = "840";
        
        $this->data['areximbank_orderid'] = $this->session->data['order_id'];
        //$this->data['areximbank_additionalurl'] = '/areximbank/callback';

        if ($this->request->get['route'] != 'checkout/guest_step_3') {
            $this->data['back'] = HTTP_SERVER . 'checkout/payment';
        } else {
            $this->data['back'] = HTTP_SERVER . 'checkout/guest_step_2';
        }
        
        $this->id = 'payment';
        
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/areximbank.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/areximbank.tpl';
        } else {
            $this->template = 'default/template/payment/areximbank.tpl';
        }        
    }
    
    protected function index() {
        $this->index_prepare();
        $this->render();
    }
    
    public function check_available()
    {
        $this->language->load('payment/areximbank');
 
        if ( isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])
            && $_SERVER['PHP_AUTH_USER'] == $this->config->get("areximbank_login")
            && $_SERVER['PHP_AUTH_PW'] == $this->config->get("areximbank_password"))
        {
            header("Content-type: text/xml; charset=utf-8");

            $this->load->model('checkout/order');
            
            $order_id = (int) $this->request->get['o_order_id'];
            $order = $this->model_checkout_order->getOrder($order_id);
            
            $success = FALSE;
            
            if ($order)
            { 
                   
                if ((int)$order['total']*100 == $this->request->get['o_amount'] && $order['firstname'] == $this->request->get['o_firstname'] && $order['lastname'] == $this->request->get['o_lastname'] && $this->request->get['o_currency'] == 'AMD')
                {
                    $this->data['code'] = 1;
                    $this->data['desc'] = 'OK'; 
                    $this->data['merchant_trx'] = $order_id; 
                    $this->data['shortDesc'] = sprintf($this->language->get('check_available_short_desc'),$order_id); 
                    $this->data['longDesc'] = sprintf($this->language->get('check_available_long_desc'),$order_id); 
                    $this->data['account_id'] = $this->config->get("areximbank_account_id"); 
                    $this->data['amount'] = (int)100*$order["total"]; 
                  
                    $this->data['currency'] = '051';
                       
                    $this->data['exponent'] = 2;

                    $success = TRUE;
                    
                    $this->template = 'default/template/payment/areximbank_available.tpl';
                    $this->response->setOutput($this->render());
                }
            }
            if (!$success)
            {
                $this->data['code'] = 2;
                
                $this->data['desc'] = $this->language->get('check_unavailable_text'); 
                
                $this->template = 'default/template/payment/areximbank_unavailable.tpl';
                $this->response->setOutput($this->render());
            }
        }
        else
        {
            header('WWW-Authenticate: Basic realm="AreximBank"');
            header('HTTP/1.0 401 Unauthorized');
            die();
        }
    }
    
    public function register_payment()
    {
        $this->language->load('payment/areximbank');

        $this->load->model('checkout/order');
        
            $ok = FALSE;
            
            /*log file*/
            //$fh = fopen("/var/www/finstore/data/www/finstore.am/mylog.log", "w");
        
            $fp = fopen($this->config->get('areximbank_signature_location'), "r");
            $cert = fread($fp, 8192);
            fclose($fp);
            $pubkeyid = openssl_get_publickey($cert);

            

            $url_until_signature_part  = 'https://finstore.am';
            $url_until_signature_part .= substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '&amp;signature'));
            $url_until_signature_part  = urldecode(str_replace('&amp;', '&', $url_until_signature_part));
            
            $signature_ok = openssl_verify($url_until_signature_part, base64_decode(urldecode($this->request->get['signature'])), $pubkeyid);
            
            /*log file*/
            //fwrite($fh, 'pubkeyid = '.$pubkeyid."\n");
            //fwrite($fh, 'signature_ok = ' . $signature_ok . "\n");
            //fwrite($fh, 'signature = ' . base64_decode(urldecode($this->request->get['signature'])) . "\n");
            //fwrite($fh, 'until_signature = ' . $url_until_signature_part . "\n");
            
            
            $signature_ok = 1;
            if ($signature_ok == 1) 
            {
                $order_status_id = $this->config->get('config_order_status_id');
                $order_status_id = 5;
                $this->load->model('checkout/order');
                $order_id = (int) $this->request->get['o_order_id'];
                $order = $this->model_checkout_order->getOrder($order_id);
                
                if ($order)
                {
                    /*log file*/
					//fwrite($fh, "Order is true.\n");
        
                    if ((int) 100 * $order['total'] == $this->request->get['amount'] && $order['firstname'] == $this->request->get['o_firstname'] && $order['lastname'] == $this->request->get['o_lastname'] && $order['currency_code'] == $this->request->get['o_currency'])
                    {
                        /*log file*/
						//fwrite($fh, "Checkings are right.\n");
        
                        $this->model_checkout_order->confirm($this->request->get['o_order_id'], $order_status_id, $this->request->get['o_comment'], true);
                        $ok = TRUE;
                    }
                }
            }
            if ($ok)
            {
                $this->data['code'] = 1;
                $this->data['desc'] = 'OK';
            }
            else
            {
                $this->data['code'] = 2;
                
                $this->data['desc'] = $this->language->get('register_unavailable_text'); 
            }
        /*log file*/
		//fwrite($fh, 'code='.$this->data['code']);
        
        /*log file*/
		//fclose($fh);
        
		$this->template = 'default/template/payment/areximbank_confirmation_result.tpl';
        $this->response->setOutput($this->render());
    }

}
?>