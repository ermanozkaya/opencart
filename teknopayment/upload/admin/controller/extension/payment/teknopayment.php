<?php
class ControllerExtensionPaymentTeknopayment extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/payment/teknopayment');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $post['payment_teknopayments'] = array();
            if(isset($this->request->post['payment_teknopayments'])){
                foreach($this->request->post['payment_teknopayments'] as $payment_teknopayment){
                    $post['payment_teknopayments'][] = $payment_teknopayment;
                }
            }
            $this->model_setting_setting->editSetting('payment_teknopayments',$post);
			$this->model_setting_setting->editSetting('payment_teknopayment', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment/teknopayment', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true));
		}
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/payment/teknopayment', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/payment/teknopayment', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=payment', true);
        
        $data['teknopayments'] = $this->config->get('payment_teknopayments');
        if(empty( $data['teknopayments'] ))  $data['teknopayments'] = array(array());
        $fields = array('name', 'total', 'order_status_id', 'geo_zone_id', 'status', 'description', 'sort_order');
        if($data['teknopayments']){
            foreach($data['teknopayments'] as $teknopayment_key=>$teknopayment){
                foreach($fields as $field){
                    if (isset($this->request->post['payment_teknopayments']) && isset($this->request->post['payment_teknopayments'][$field])) {
                        $data['teknopayments'][$teknopayment_key][$field] = $this->request->post['payment_teknopayment'][$field];
                    } else {
                        $data['teknopayments'][$teknopayment_key][$field] = isset($teknopayment[$field])?$teknopayment[$field]:'';
                    }
                }
            }
        } 
		if (isset($this->request->post['payment_teknopayment_status'])) {
			$data['payment_teknopayment_status'] = $this->request->post['payment_teknopayment_status'];
		} else {
			$data['payment_teknopayment_status'] = $this->config->get('payment_teknopayment_status');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		$data['current_language_code'] = $this->config->get('config_language');

		$data['last_payment_id'] = count($data['teknopayments']);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/payment/teknopayment', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/payment/teknopayment')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}