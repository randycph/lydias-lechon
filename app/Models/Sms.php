<?php

namespace App\Models;
use App\EcommerceModel\DeliveryStatus;
use App\Services\ItextmoSmsService;
use App\EcommerceModel\SalesHeader;

class Sms
{

	public function send_sms($receiver, $type, $transaction, $driver = null){
		//return '';
		if(substr($receiver, 0, 2) == '09'){
			$receiver = '+639'.substr($receiver, 2);
		}
	
		
		if($type == 'new_order'){
			$this->new_order($receiver, $transaction);
		} elseif($type == 'confirm_order') {
			$this->confirm_order($receiver, $transaction);
		} elseif($type == 'delivery_update') {	
			$this->delivery_update($receiver, $transaction);
		} elseif($type == 'payment_update') {
			$this->payment_update($receiver, $transaction);
		} elseif($type == 'payment_new') {
			$this->payment_new($receiver, $transaction);
		} elseif($type == 'welcome') {
			$this->welcome($receiver, $transaction);
		} elseif($type == 'delivery_assigned') {
			$this->delivery_assigned($receiver, $transaction, $driver);
		} elseif($type == 'new_order_delivery') {
			$this->new_order_delivery($receiver, $transaction);
		}

	}

	public function new_order_delivery($receiver, $transaction)
	{
		try {
			$message = "Thank you for ordering at Lydia's Lechon. You will receive further instructions soon.";

			$sms = new ItextmoSmsService();
			$sms->send($receiver, $message);

		} catch (\Exception $e) {
			logger()->error('SMS Error: New order delivery = '.$e->getMessage());
		}
	}

	public function delivery_assigned($receiver, $transaction, $driver = null)
	{
		$name = $driver->name;
		$orderNumber = $transaction->order_number;

		try {
			$message = "Happy Day, $name!. Order #$orderNumber has been assigned to you. Please check the details in the system.";

			$sms = new ItextmoSmsService();
			$sms->send($receiver, $message);
		} catch (\Exception $e) {
			logger()->error('SMS Error: Delivery assigned = '.$e->getMessage());
		}
	}

	public function delivery_update($receiver, $order)
	{
		try {
			if ($order->delivery_status == 'Delivered/Picked Up') {
				if ($order->delivery_type == 'Store Pickup') {
					$message = "Happy Day, $order->customer_name!. Your order is now ready for pickup. Thank you for choosing Lydia's Lechon!";
				} else {
					$message = "Happy Day, $order->customer_name!.  Your order has been successfully delivered. Thank you for choosing Lydia's Lechon!";
				}
			} else {
				$message = "Happy Day, $order->customer_name!. Your order is now out for delivery. Please keep your provided contact number active so we can reach you if needed. Thank you!";
			}

			$sms = new ItextmoSmsService();
			$sms->send($receiver, $message);
		} catch (\Throwable $th) {
			logger()->error('SMS Error: Delivery update = '.$th->getMessage());
		}
	}

	public function payment_update($receiver, $payment)
	{
		try {
			$stat = ($payment->status == 'PAID' ? 'APPROVED' : 'DISAPPROVED');
			$confirmed_status_payments = ['COD','Oth','Sign-Chit','Ex-deal','Ok Order'];

			if ($stat == 'APPROVED' && in_array($payment->payment_type, $confirmed_status_payments)) {
				$stat = 'CONFIRMED';
			} else {
				if ($stat == 'APPROVED') {
					$order = SalesHeader::whereId($payment->sales_header_id)->first();

					if ($order->delivery_type == 'Store Pickup') {
						$message = "Happy Day, $order->customer_name!. Thank you for your payment of Php " . number_format($payment->amount, 2) . " for order #" . $order->order_number . ". You will be notified once your order is ready for pickup.";
					} else {
						$message = "Happy Day, $order->customer_name!. Thank you for your payment of Php " . number_format($payment->amount, 2) . " for order #" . $order->order_number . ". You will be notified once your order is ready for delivery.";
					}

					$sms = new ItextmoSmsService();
					$sms->send($receiver, $message);
				}
			}
		} catch (\Throwable $th) {
			logger()->error('SMS Error: Payment update = '.$th->getMessage());
		}
	}

	public function welcome($receiver, $user){		

		$name = $user->name;

		try {
			$message = "Happy Day, $name!. Welcome to Lydia's Lechon! We're excited to have you on board.";

			$sms = new ItextmoSmsService();
			$sms->send($receiver, $message);
		} catch (\Exception $e) {
			logger()->error('SMS Error: welcome = '.$e->getMessage());
		}
	}

	public function payment_new($receiver, $payment)
	{		
		try {
			$confirmed_status_payments = ['COD','Oth','Sign-Chit','Ex-deal','Ok Order'];
			$order = SalesHeader::whereId($payment->sales_header_id)->first();

			if (!in_array($payment->payment_type, $confirmed_status_payments)) {
				$message = "Happy Day, $order->customer_name!. A Payment of ".number_format($payment->amount,2)." was paid for your ORDER: ".$order->order_number;

				$sms = new ItextmoSmsService();
				$sms->send($receiver, $message);
			}
		} catch (\Throwable $th) {
			logger()->error('SMS Error: New payment = '.$th->getMessage());
		}
	}

	public function new_order($receiver, $order){
		
		try {
			$message = "Happy Day, $order->customer_name!. Thank you for choosing Lydia's Lechon. Your order ".$order->order_number." for order date ".date('m/d/Y', strtotime($order->created_at))." is currently being processed. Kindly wait as we validate your payment and confirm your order.";

			$sms = new ItextmoSmsService();
			$sms->send($receiver, $message);
		} catch (\Throwable $th) {
			logger()->error('SMS Error: New order = '.$th->getMessage());
		}
	}

	public function confirm_order($receiver, $order)
	{
		$sale = SalesHeader::withTrashed()->with('items')->find($order->id);
		$item = $sale->items->first();
		$date_needed = date('m/d/Y', strtotime($item->delivery_date));
		try {
			$message = "Happy Day, $order->customer_name!. Your order  Order Date (".date('m/d/Y', strtotime($order->created_at)).")  Date Needed - ". $date_needed ." is now confirmed. Thank you for choosing Lydia's Lechon!";

			$sms = new ItextmoSmsService();
			$sms->send($receiver, $message);
		} catch (\Throwable $th) {
			logger()->error('SMS Error: Confirm order = '.$th->getMessage());
		}
	}
}
