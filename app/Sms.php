<?php

namespace App;
use App\EcommerceModel\DeliveryStatus;
class Sms
{

	public function send_sms($receiver, $type, $transaction){
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

	}

	public function delivery_update($receiver, $order){
		if($order->delivery_status == 'Delivered'){


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
		$headers[] = 'Authorization: Bearer dwD2PXjYKV9kQv6KAI1l4ohYEjuOEwIoeoTPtwrEkU';
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
    			$headers[] = 'Authorization: Bearer dwD2PXjYKV9kQv6KAI1l4ohYEjuOEwIoeoTPtwrEkU';
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
			$headers[] = 'Authorization: Bearer dwD2PXjYKV9kQv6KAI1l4ohYEjuOEwIoeoTPtwrEkU';
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
		
		$message = "Hi $order->customer_name. Thank you for choosing Lydia's Lechon! Your order #".$order->order_number." is currently being process, kindly wait for order confirmation upon validation of your payment.";
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://api.wavecell.com/sms/v1/Lydia_MKT/single');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"source\":\"Lydias\",\"destination\":\"$receiver\",\"text\":\"$message\"}");

		$headers = array();
		$headers[] = 'Authorization: Bearer dwD2PXjYKV9kQv6KAI1l4ohYEjuOEwIoeoTPtwrEkU';
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    //echo 'Error:' . curl_error($ch);
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
		$headers[] = 'Authorization: Bearer dwD2PXjYKV9kQv6KAI1l4ohYEjuOEwIoeoTPtwrEkU';
		$headers[] = 'Content-Type: application/json';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		if (curl_errno($ch)) {
		    //echo 'Error:' . curl_error($ch);
		}
		curl_close($ch);
	}
}
