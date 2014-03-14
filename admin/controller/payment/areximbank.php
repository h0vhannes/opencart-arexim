<?php
class ControllerPaymentAREXIMBANK extends Controller {
    private $error = array(); 

    public function index() {
        $this->load->language('payment/areximbank');

        $this->document->setTitle($this->language->get('heading_title'));
        
        $this->load->model('setting/setting');
            
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
            $this->model_setting_setting->editSetting('areximbank', $this->request->post);                
            
            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
        }

        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        //$this->data['text_all_zones'] = $this->language->get('text_all_zones');
        
        $this->data['entry_rooturl'] = $this->language->get('entry_rooturl');
        //$this->data['entry_hostid'] = $this->language->get('entry_hostid');
        $this->data['entry_merchant'] = $this->language->get('entry_merchant');
        $this->data['entry_amount_id'] = $this->language->get('entry_amount_id');
        $this->data['entry_signature_location'] = $this->language->get('entry_signature_location');
        
        //$this->data['entry_tid'] = $this->language->get('entry_tid');
        //$this->data['entry_security'] = $this->language->get('entry_security');
        //$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $this->data['entry_status'] = $this->language->get('entry_status');
        
        $this->data['entry_account_id'] = $this->language->get('entry_account_id');
        
        $this->data['entry_page_id'] = $this->language->get('page_id');
        
        $this->data['entry_login'] = $this->language->get('login');
        
        $this->data['entry_password'] = $this->language->get('password');
        
        $this->data['entry_testmode'] = $this->language->get('entry_testmode');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['tab_general'] = $this->language->get('tab_general');

        if (isset($this->error['warning'])) {
            $this->data['error_warning'] = $this->error['warning'];
        } else {
            $this->data['error_warning'] = '';
        }

        if (isset($this->error['rooturl'])) {
            $this->data['error_rooturl'] = $this->error['rooturl'];
        } else {
            $this->data['error_rooturl'] = '';
        }
        
        if (isset($this->error['signature_location'])) {
            $this->data['error_signature_location'] = $this->error['signature_location'];
        } else {
            $this->data['error_signature_location'] = '';
        }

         /*if (isset($this->error['hostid'])) {
            $this->data['error_hostid'] = $this->error['hostid'];
        } else {
            $this->data['error_hostid'] = '';
        }*/

        if (isset($this->error['merchant'])) {
            $this->data['error_merchant'] = $this->error['merchant'];
        } else {
            $this->data['error_merchant'] = '';
        }
        
        if (isset($this->error['account_id'])) {
            $this->data['error_account_id'] = $this->error['account_id'];
        } else {
            $this->data['error_account_id'] = '';
        }
        
        if (isset($this->error['page_id'])) {
            $this->data['error_page_id'] = $this->error['page_id'];
        } else {
            $this->data['error_page_id'] = '';
        }
        
        if (isset($this->error['login'])) {
            $this->data['error_login'] = $this->error['login'];
        } else {
            $this->data['error_login'] = '';
        }
        
        if (isset($this->error['password'])) {
            $this->data['error_password'] = $this->error['password'];
        } else {
            $this->data['error_password'] = '';
        }

         /*if (isset($this->error['tid'])) {
            $this->data['error_tid'] = $this->error['tid'];
        } else {
            $this->data['error_tid'] = '';
        }*/

         /*if (isset($this->error['security'])) {
            $this->data['error_security'] = $this->error['security'];
        } else {
            $this->data['error_security'] = '';
        }*/

          $this->data['breadcrumbs'] = array();

           $this->data['breadcrumbs'][] = array(
               'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
               'text'      => $this->language->get('text_home'),
              'separator' => FALSE
           );

           $this->data['breadcrumbs'][] = array(
               'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
               'text'      => $this->language->get('text_payment'),
              'separator' => ' :: '
           );

           $this->data['breadcrumbs'][] = array(
               'href'      => $this->url->link('payment/areximbank', 'token=' . $this->session->data['token'], 'SSL'),
               'text'      => $this->language->get('heading_title'),
              'separator' => ' :: '
           );
                
        $this->data['action'] = $this->url->link('payment/areximbank', 'token=' . $this->session->data['token'], 'SSL');
        
        $this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
        
        if (isset($this->request->post['areximbank_rooturl'])) {
            $this->data['areximbank_rooturl'] = $this->request->post['areximbank_rooturl'];
        } else {
            $this->data['areximbank_rooturl'] = $this->config->get('areximbank_rooturl');
        }

        /*if (isset($this->request->post['arca_hostid'])) {
            $this->data['areximbank_hostid'] = $this->request->post['areximbank_hostid'];
        } else {
            $this->data['areximbank_hostid'] = $this->config->get('areximbank_hostid');
        }*/

        if (isset($this->request->post['areximbank_merchant'])) {
            $this->data['areximbank_merchant'] = $this->request->post['areximbank_merchant'];
        } else {
            $this->data['areximbank_merchant'] = $this->config->get('areximbank_merchant');
        }

        /*if (isset($this->request->post['areximbank_tid'])) {
            $this->data['areximbank_tid'] = $this->request->post['areximbank_tid'];
        } else {
            $this->data['areximbank_tid'] = $this->config->get('areximbank_tid');
        }*/

        /*if (isset($this->request->post['areximbank_security'])) {
            $this->data['areximbank_security'] = $this->request->post['areximbank_security'];
        } else {
            $this->data['areximbank_security'] = $this->config->get('areximbank_security');
        }*/

        /*if (isset($this->request->post['areximbank_geo_zone_id'])) {
            $this->data['areximbank_geo_zone_id'] = $this->request->post['areximbank_geo_zone_id'];
        } else {
            $this->data['areximbank_geo_zone_id'] = $this->config->get('areximbank_geo_zone_id'); 
        }*/ 

        //$this->load->model('localisation/geo_zone');
                                        
        //$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        
        if (isset($this->request->post['areximbank_status'])) {
            $this->data['areximbank_status'] = $this->request->post['areximbank_status'];
        } else {
            $this->data['areximbank_status'] = $this->config->get('areximbank_status');
        }
        
        if (isset($this->request->post['areximbank_testmode'])) {
            $this->data['areximbank_testmode'] = $this->request->post['areximbank_testmode'];
        } else {
            $this->data['areximbank_testmode'] = $this->config->get('areximbank_testmode');
        }

        if (isset($this->request->post['areximbank_sort_order'])) {
            $this->data['areximbank_sort_order'] = $this->request->post['areximbank_sort_order'];
        } else {
            $this->data['areximbank_sort_order'] = $this->config->get('areximbank_sort_order');
        }
        
        if (isset($this->request->post['areximbank_account_id'])) {
            $this->data['areximbank_account_id'] = $this->request->post['areximbank_account_id'];
        } else {
            $this->data['areximbank_account_id'] = $this->config->get('areximbank_account_id');
        }
        
        if (isset($this->request->post['areximbank_page_id'])) {
            $this->data['areximbank_page_id'] = $this->request->post['areximbank_page_id'];
        } else {
            $this->data['areximbank_page_id'] = $this->config->get('areximbank_page_id');
        }
        
        if (isset($this->request->post['areximbank_login'])) {
            $this->data['areximbank_login'] = $this->request->post['areximbank_login'];
        } else {
            $this->data['areximbank_login'] = $this->config->get('areximbank_login');
        }
        
        if (isset($this->request->post['areximbank_signature_location'])) {
            $this->data['areximbank_signature_location'] = $this->request->post['areximbank_signature_location'];
        } else {
            $this->data['areximbank_signature_location'] = $this->config->get('areximbank_signature_location');
        }
        
        if (isset($this->request->post['areximbank_password'])) {
            $this->data['areximbank_password'] = $this->request->post['areximbank_password'];
        } else {
            $this->data['areximbank_password'] = $this->config->get('areximbank_password');
        }
        
        $this->template = 'payment/areximbank.tpl';
        $this->children = array(
            'common/header',    
            'common/footer'    
        );
        
        $this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
    }

    private function validate() {
        if (!$this->user->hasPermission('modify', 'payment/areximbank')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
        
        if (!$this->request->post['areximbank_rooturl']) {
            $this->error['rooturl'] = $this->language->get('error_rooturl');
        }

        /*if (!$this->request->post['areximbank_hostid']) {
            $this->error['hostid'] = $this->language->get('error_hostid');
        }*/

        if (!$this->request->post['areximbank_merchant']) {
            $this->error['merchant'] = $this->language->get('error_merchant');
        }

        /*if (!$this->request->post['areximbank_tid']) {
            $this->error['tid'] = $this->language->get('error_tid');
        }*/

        /*if (!$this->request->post['areximbank_security']) {
            $this->error['security'] = $this->language->get('error_security');
        }*/

        if (!$this->error) {
            return TRUE;
        } else {
            return FALSE;
        }    
    }
}

?>