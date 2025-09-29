<?php

namespace App\Models;
use App\EcommerceModel\DeliveryStatus;
class Sms
{

	public function send_sms($receiver, $type, $transaction, $driver = null){
		//return '';
		if(substr($receiver, 0, 2) == '09'){
			$receiver = '+639'.substr($receiver, 2);
		}
	
		
		if($type == 'new_order'){
			$send_to_customer = $this->new_order($receiver, $transaction);
		}
		elseif($type == 'confirm_order'){
			$send_to_customer = $this->new_order($receiver, $transaction);
		}
		elseif($type == 'delivery_update'){	
			$send_to_customer = $this->delivery_update($receiver, $transaction);
		}
		elseif($type == 'payment_update'){
			$send_to_customer = $this->payment_update($receiver, $transaction);
		}
		elseif($type == 'payment_new'){
			$send_to_customer = $this->payment_new($receiver, $transaction);
		}
		elseif($type == 'welcome'){
			$send_to_customer = $this->welcome($receiver, $transaction);
		}
		elseif($type == 'delivery_assigned'){
			$send_to_customer = $this->delivery_assigned($receiver, $transaction, $driver);
		}
		elseif($type == 'new_order_delivery'){
			$send_to_customer = $this->new_order_delivery($receiver, $transaction);
		}

	}

	public function new_order_delivery($receiver, $transaction){

		try {
			$message = "Thank you for ordering at Lydia's Lechon. You will receive further instructions soon.";
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

			$headers = array();
			$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
			    //echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
		} catch (\Exception $e) {
			logger()->error('SMS Error: '.$e->getMessage());
		}
	
	}

	public function delivery_assigned($receiver, $transaction, $driver = null){

		$name = $driver->name;
		$orderNumber = $transaction->order_number;

		try {
			$message = "Hi $name. Order #$orderNumber has been assigned to you. Please check the details in the system.";
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

			$headers = array();
			$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
			    //echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
		} catch (\Exception $e) {
			logger()->error('SMS Error: '.$e->getMessage());
		}
	}

	public function delivery_update($receiver, $order){
		if($order->delivery_status == 'Delivered/Picked Up'){


			if($order->delivery_type == 'Store Pickup'){
				$message = "Hi $order->customer_name. Your order is now ready for pickup. Thank you for choosing Lydia's Lechon. Your Everyday Lechon Happiness!";
			}
			else{
				$message = "Hi $order->customer_name. Your order has been successfully delivered. Thank you for choosing Lydia's Lechon. Your Everyday Lechon Happiness!";
			}

			
		}
		else{
			$message = "Hi $order->customer_name. Your order is on its way. Thank you for choosing Lydia's Lechon. Your Everyday Lechon Happiness!";
		}
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

		$headers = array();
		$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    //echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	}

	public function payment_update($receiver, $payment){
		$stat = ($payment->status == 'PAID' ? 'APPROVED' : 'DISAPPROVED');
		$confirmed_status_payments = ['COD','Oth','Sign-Chit','Ex-deal','Ok Order'];

		if($stat == 'APPROVED' && in_array($payment->payment_type, $confirmed_status_payments)){
			$stat = 'CONFIRMED';
		}
		else{
	        if($stat == 'APPROVED'){
    			$order = \App\EcommerceModel\SalesHeader::whereId($payment->sales_header_id)->first();
    
    			if($order->delivery_type == 'Store Pickup'){
    				$message = "Hi $order->customer_name. Thank you for your payment of Php ".number_format($payment->amount,2)." for order #".$order->order_number.". You will be notified once your order is ready for pickup.";
    			}
    			else{
    				$message = "Hi $order->customer_name. Thank you for your payment of Php ".number_format($payment->amount,2)." for order #".$order->order_number.". You will be notified once your order is ready for delivery.";
    			}
    			$ch = curl_init();
    
    			curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    			curl_setopt($ch, CURLOPT_POST, 1);
    			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");
    
    			$headers = array();
    			$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
    			$headers[] = 'Content-Type: application/json';
    			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
    			$result = curl_exec($ch);
    			if (curl_errno($ch)) {
    			    //echo 'Error:' . curl_error($ch);
    			}
    			curl_close($ch);
	        }
		}
	}

	public function welcome($receiver, $user){		

		$name = $user->name;

		try {
			$message = "Hi $name. Welcome to Lydia's Lechon! We're excited to have you on board.";
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

			$headers = array();
			$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
			    //echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
		} catch (\Exception $e) {
			logger()->error('SMS Error: '.$e->getMessage());
		}
	}

	public function payment_new($receiver, $payment){		

		$confirmed_status_payments = ['COD','Oth','Sign-Chit','Ex-deal','Ok Order'];
		$order = \App\EcommerceModel\SalesHeader::whereId($payment->sales_header_id)->first();

		if(!in_array($payment->payment_type, $confirmed_status_payments)){
			$message = "Hi $order->customer_name. A Payment of ".number_format($payment->amount,2)." was paid for your ORDER: ".$order->order_number;
			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

			$headers = array();
			$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
			$headers[] = 'Content-Type: application/json';
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			$result = curl_exec($ch);
			if (curl_errno($ch)) {
			    //echo 'Error:' . curl_error($ch);
			}
			curl_close($ch);
		}
	}

	public function new_order($receiver, $order){
		
// $curl = curl_init();

// curl_setopt_array($curl, [
//   CURLOPT_URL => "https://sms.8x8.com/api/v1/subaccounts/Lydia_MKT/messages",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//   CURLOPT_SSL_VERIFYPEER => false,
//   CURLOPT_SSL_VERIFYHOST  => false,
//   CURLOPT_POSTFIELDS => json_encode([
//     'encoding' => 'AUTO',
//     'track' => null,
//     'destination' => '+639174128392',
//     'text' => 'hello wrld'
//   ]),
//   CURLOPT_HTTPHEADER => [
//     "accept: application/json",
//     "authorization: Bearer ' . config('services.sms.api_key'),
//     "content-type: application/json"
//   ],
// ]);

// $response = curl_exec($curl);
// $err = curl_error($curl);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//   echo $response;
// }

		$message = "Hi $order->customer_name. Thank you for choosing Lydia's Lechon! Your order #".$order->order_number." is currently being process, kindly wait for order confirmation upon validation of your payment.";
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://sms.8x8.com/api/v1/subaccounts/Lydia_MKT/messages');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

		$headers = array();
		$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    // echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	}

	public function confirm_order($receiver, $order){
		
		$message = "Hi $order->customer_name. Thank you for choosing Lydia's Lechon! Your order #$order->order_number is currently being process, kindly wait for order ";
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

		$headers = array();
		$headers[] = 'Authorization: Bearer ' . config('services.sms.api_key');
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    //echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	}
}
