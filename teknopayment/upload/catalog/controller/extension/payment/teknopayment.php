<?php
class ControllerExtensionPaymentTeknopayment extends Controller {
    
   function __construct($parent) {
      parent::__construct($parent);

   }   
    
	public function index() {
		return $this->load->view('extension/payment/cod');
	}

	public function confirm() {

        $json['error'] = 'Ödeme türü yapılandırma hatası!';
        $payment_method = current(explode('/', $this->session->data['payment_method']['code']));
		if ($payment_method == 'teknopayment') {
            $parts = explode('_', $this->session->data['payment_method']['code']);
            $payment_id = end($parts);
            $teknopayments = $this->config->get('payment_teknopayments');
            if($teknopayments){
                $teknopayment = isset($teknopayments[$payment_id])?$teknopayments[$payment_id]:array();
            } else {
                $teknopayment = array();
            }
            if($teknopayment){
                $this->load->model('checkout/order');
                $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $teknopayment['order_status_id']);
                $json['redirect'] = $this->url->link('checkout/success');
                $json['error'] = '';
            }
		}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));		
	}
    function method_common($payment_id){
        $teknopayments = $this->config->get('payment_teknopayments');
        if($teknopayments){
            $data['teknopayment'] = isset($teknopayments[$payment_id])?$teknopayments[$payment_id]:array();
        } else {
            $data['teknopayment'] = array();
        }
        $data['current_language_code']  = $this->session->data['language'];
        return $this->load->view('extension/payment/teknopayment', $data);
    }
    function method_0(){
        return $this->method_common(0);
    }
    function method_1(){
        return $this->method_common(1);
    }
    function method_2(){
        return $this->method_common(2);
    }
    function method_3(){
        return $this->method_common(3);
    }
    function method_4(){
        return $this->method_common(4);
    }
    function method_5(){
        return $this->method_common(8);
    }
    function method_6(){
        return $this->method_common(6);
    }
    function method_7(){
        return $this->method_common(7);
    }
    function method_8(){
        return $this->method_common(8);
    }
    function method_9(){
        return $this->method_common(9);
    }
}
