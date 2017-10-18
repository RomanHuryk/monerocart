<?php
class ControllerExtensionPaymentMonero extends Controller
{
    private $error = array();
    private $settings = array();
    //Config page
    public function index()
    {
        $this->load->language('extension/payment/monero');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('setting/setting');
        //new config
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('monero', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/extension', 'token=' . $this->session->data['token'], true));
        }
        //language data
        $data['heading_title'] = $this->language->get('heading_title');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        $data['text_all_zones'] = $this->language->get('text_all_zones');
        $data['text_yes'] = $this->language->get('text_yes');
        $data['text_no'] = $this->language->get('text_no');
        $data['text_edit'] = $this->language->get('text_edit');
        
        
        $data['monero_address_text'] = $this->language->get('monero_address_text');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
        $data['help_total'] = $this->language->get('help_total');
        //Errors
        $data['error_warning'] = isset($this->error['warning']) ? $this->error['warning'] : '';
        
        //Zones, order statuses
        $this->load->model('localisation/geo_zone');
        $data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
        $this->load->model('localisation/order_status');
        $data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
       
       // Values for Settings
       
            $this->request->post['monero_address'] : $this->config->get('monero_address');
        
       
       
        $data['breadcrumbs'] = array();
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'token=' . $this->session->data['token'] . '&type=payment', true)
        );
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/payment/monero', 'token=' . $this->session->data['token'], true)
        );
        $data['action'] = $this->url->link('extension/payment/monero', 'token=' . $this->session->data['token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'token=' . $this->session->data['token'], true);
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $this->response->setOutput($this->load->view('extension/payment/monero.tpl', $data));
    }
  
    private function validate()
    {
        //permisions
        if (!$this->user->hasPermission('modify', 'extension/payment/monero')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }
               
    }
    public function install()
    {
        $this->load->model('extension/payment/monero');
        $this->load->model('setting/setting');
        
        $this->model_setting_setting->editSetting('monero', $this->settings);
        $this->model_extension_payment_monero->createDatabaseTables();
    }
    public function uninstall()
    {
        $this->load->model('extension/payment/monero');
        $this->load->model('setting/setting');
        $this->model_setting_setting->deleteSetting('monero');
        $this->model_extension_payment_monero->dropDatabaseTables();
    }
}
