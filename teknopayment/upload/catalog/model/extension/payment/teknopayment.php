<?php
class ModelExtensionPaymentTeknopayment extends Model {
	public function getMethod($address, $total) {
		$this->load->language('extension/payment/teknopayment');



		$method_data = array();
        $teknopayments = $this->config->get('payment_teknopayments');
        $payments = array();
        if($teknopayments ){
            foreach($teknopayments as $payment_key=>$teknopayment){
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$teknopayment['geo_zone_id']. "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
                //die($teknopayment['status'].'**'.$teknopayment['total'].'**'.$total);
                if ($teknopayment['status'] > 0 && $teknopayment['total'] > $total) {
                    $status = false;
                } elseif (!$teknopayment['geo_zone_id']) {
                    $status = true;
                } elseif ($query->num_rows) {
                    $status = true;
                } else {
                    $status = false;
                }
                $current_language_code = $this->session->data['language'];

                if ( $status && $teknopayment['status']) {
                    $payments[] = array(
                        'code'       => 'teknopayment/method_'.$payment_key,
                        'title'      => $teknopayment['title'][$current_language_code],
                        'terms'      => '',
                        'sort_order' => $teknopayment['sort_order']
                    );
                }    
            }
            $method_data = array(
            'code'       => 'teknopayment',
            'title'      => $this->language->get('text_title'),
            'terms'      => '',
            'sort_order' => $this->config->get('payment_cod_sort_order'),
            'payments' => $payments
            );
        }

		return $method_data;
	}
}
