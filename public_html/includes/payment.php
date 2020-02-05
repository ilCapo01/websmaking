<?php

// https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/formbasics/

class Payment {

	function createPayment($data = array()) {
	    $vat_percentage = 17;
		$vat_calc = ($data['amount'] * $vat_percentage) / 100;

		$form = array('cmd' => '_s-xclick', 'hosted_button_id' => 'YVR9REZ2CNFSN',
		    'return' => urlencode('https://www.websmaking.com/?do=premium'), 
		'cancel' => urlencode('https://www.websmaking.com/?do=premium'), 
		'success' => urlencode('https://www.websmaking.com/?do=success'), 
		'notify_url' => urlencode('https://www.websmaking.com/?do=verify-paypal'), 
		'currency_code' => $data['currency'], 'amount' => $data['amount'], 'item_name' => $data['item_name'], 'item_number' => $data['item_number'], 'custom' => $data['siteid'], 'quantity' => '1', 
		'cpp_logo_image' => 'https://i.websmaking.com/images/custom-logo.png');
		$pay = $this->sendRequest('https://www.paypal.com/cgi-bin/webscr', $form);
	    //https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YVR9REZ2CNFSN
	    
		// Redirect to payment url on Paypal.
		preg_match("!\r\n(?:location|URI): *(.*?) *\r\n!", $pay, $matches);
		header('location: '.$matches[1]);
		die;
	}

	private function sendRequest($url, $data) {
        $data = http_build_query($data);
        
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		   'Content-type: application/x-www-form-urlencoded',
		   'Content-length: '.strlen($data)
		));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true); 

        $server_output = curl_exec($ch);
        $headers = substr($server_output, 0, curl_getinfo($ch)["header_size"]); //split out header
		
		curl_close ($ch);
		return $headers;
	}

}

$payment = new Payment;
?>
