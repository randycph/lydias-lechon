<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\SalesPayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class PaymayatestController extends Controller
{

    public function pk(){
        return base64_encode('pk-lNAUk1jk7VPnf7koOT1uoGJoZJjmAxrbjpj6urB8EIA');  // test
        //return base64_encode('pk-bzhgBQYUAtCvLa0PEPQiWGHeqrDLCEAnNKi7LhJLECY'); // beta
        //return base64_encode('pk-2oMK4D8wMUbKXay0VjLHk84OiKIuTfA2YsrdSH9o844');
            
            
    }

    public function sk(){
        return base64_encode('sk-fzukI3GXrzNIUyvXY3n16cji8VTJITfzylz5o5QzZMC'); //test
        //return base64_encode('sk-XU2KylKnROUoiOkxzZ4hSEGDssFqIqDtsKhjW2i6mlV');  //beta
        //return base64_encode('sk-iLyM468U8VeXEOywY2ALFyxjuQCWDGS7bWagzCDccJG');  
    
    }

    public function paymaya_url(){
        return 'https://pg-sandbox.paymaya.com/checkout/v1/checkouts'; // test
        //return 'https://pg.paymaya.com/checkout/v1/checkouts/';
      
        //return 'https://pg.paymaya.com/checkout/v1/checkouts';
    }

  

    

    public function check_paymaya($id){
        $sales = SalesPayment::find($id);
        $order_number = $sales->sales->order_number;
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => "Authorization: Basic ".$this->sk()."\r\n".
                            "Content-Type: application/json\r\n"
            )
        ));

        $first_response = file_get_contents($this->paymaya_url().'/'.$sales->receipt_number, FALSE, $context);
       
        
        if($first_response === FALSE){
            die('Error');
        }

        $first_responseData = json_decode($first_response, TRUE);
        //return $first_responseData;
        if($first_responseData['paymentStatus'] == 'PAYMENT_SUCCESS'){
            $update_payment = SalesPayment::whereId($id)->update([
                'amount' => $first_responseData['totalAmount']['amount'],
                'status' => 'PAID'
            ]);

            return true;
        }

        return false;

         
    }

    public function success(){        
        $tag = $this->check_paymaya($_GET['id']);   
        $order = SalesPayment::find($_GET['id']);
        if(Auth::guest())
            return redirect()->route('profile.show_sales_summary_guest',['id' => $order->sales->HashOrderNumber, 'payment_successful' => 'yes', 'order_no' => $order->sales->order_number]);
        else
            return redirect()->route('profile.sales',['payment_successful' => 'yes', 'order_no' => $order->sales->order_number]);
    }

    public function failure(){
        $update = SalesPayment::whereId($_GET['id'])->update([
            'status' => 'CANCELLED'
        ]);
        $delete = SalesPayment::whereId($_GET['id'])->delete();
     
        $order = SalesPayment::whereId($_GET['id'])->withTrashed()->first();
        if(Auth::guest())
            return redirect()->route('profile.show_sales_summary_guest',['id' => $order->sales->HashOrderNumber, 'order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
        else
            return redirect()->route('profile.sales',['order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
    }
    public function cancel(){
        $update = SalesPayment::whereId($_GET['id'])->update([
            'status' => 'CANCELLED'
        ]);
        $delete = SalesPayment::whereId($_GET['id'])->delete();
        
        $order = SalesPayment::whereId($_GET['id'])->withTrashed()->first();
        if(Auth::guest())
            return redirect()->route('profile.show_sales_summary_guest',['id' => $order->sales->HashOrderNumber, 'order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
        else
            return redirect()->route('profile.sales',['order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
    }

    public function success_wh(Request $request){      
   
        if(strval($request->isPaid) == 1){
            if($request->status == 'PAYMENT_SUCCESS'){
                $payment = SalesPayment::where('receipt_number',$request->id)->first();

                if ($payment === null) {
                    return response('No Content', 204);                    
                }
                else{
                    if($payment->status <> 'PAID'){
                        $update_payment = SalesPayment::where('receipt_number',$request->id)->update([
                            'amount' => $request->amount,
                            'status' => 'PAID'
                        ]);
                        return response('Ok', 200);   
                    }else{
                        return response('Accepted', 202); 
                    }
                }               
            }
        }
        return response('No Content', 204); 
        
    }

    public function failure_wh(Request $request){
        $update = SalesPayment::where('receipt_number',$request->id)->update([
            'status' => 'CANCELLED'
        ]);
        $update = SalesPayment::where('receipt_number',$request->id)->delete();
        
        return response('Ok', 200);  
    }

    public function expired_wh(Request $request){
        // $update = SalesPayment::where('receipt_number',$request->id)->update([
        //     'status' => 'CANCELLED'
        // ]);
        // $update = SalesPayment::where('receipt_number',$request->id)->delete();
        
        return response('Ok', 200);  
    }

    public function checkout_success(Request $request){       
        return response('Ok', 200);  
    }
    public function checkout_failure(Request $request){       
        return response('Ok', 200);  
    }
    public function checkout_dropout(Request $request){       
        return response('Ok', 200);  
    }
    

    public function pay(Request $request){        
        
        $sales = SalesHeader::find($request->sales_header_id); 
        $payment = SalesPayment::create([
            'sales_header_id' => $request->sales_header_id,
            'payment_type' => 'Paymaya',
            'amount' => $request->amount,
            'status'  => 'PENDING',
            'payment_date'  => date('Y-m-d'),
            'receipt_number'  => '',
            'created_by' => $sales->user_id
        ]);

        $checkoutId = $this->get_checkoutId($request, $payment);

        if ($checkoutId && isset($checkoutId['checkoutId'])) {
            SalesPayment::where('id', $payment->id)->update([
                'receipt_number' => $checkoutId['checkoutId']
            ]);

            return Redirect::to($checkoutId['redirectUrl']);
        }

        return response()->json(['error' => 'Unable to create checkout'], 500);
    }

    public function paydata($id,$amount,$checkoutId){
        

    }

    public function get_checkoutId($request, $payment){

        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Authorization: Basic " . $this->pk() . "\r\n" .
                            "Content-Type: application/json\r\n",
                'content' => $this->postdata($request->sales_header_id, $request->amount, $payment),
                'ignore_errors' => true
            ]
        ]);

        $response = file_get_contents($this->paymaya_url(), false, $context);

        if ($response === FALSE) {
            dd("Error", $http_response_header);
        }

        $first_responseData = json_decode($response, TRUE);

        logger($first_responseData);

        return $first_responseData;
    }

    public function postdata($id, $amount, $payment)
    {
        $sale = SalesHeader::find($id);

        $items = [];

        foreach ($sale->items as $i) {
            $items[] = [
                "name" => $i->product_name,
                "quantity" => (int) $i->qty,
                "code" => (string) $i->product_id,
                "description" => "",
                "amount" => [
                    "value" => round($i->gross_amount, 2),
                    "details" => [
                        "discount" => 0,
                        "serviceCharge" => 0,
                        "shippingFee" => 0,
                        "tax" => 0,
                        "subtotal" => round($i->gross_amount, 2)
                    ]
                ],
                "totalAmount" => [
                    "value" => round($sale->gross_amount, 2),
                    "details" => [
                        "discount" => round($sale->discount_amount, 2),
                        "serviceCharge" => 0,
                        "shippingFee" => round($sale->delivery_fee_amount, 2),
                        "tax" => 0,
                        "subtotal" => round($sale->gross_amount, 2)
                    ]
                ]
            ];
        }

        $postData = [
            "totalAmount" => [
                "value" => round($amount, 2),
                "currency" => "PHP",
                "details" => [
                    "discount" => 0,
                    "serviceCharge" => 0,
                    "shippingFee" => round($sale->delivery_fee_amount, 2),
                    "tax" => 0,
                    "subtotal" => round($amount - $sale->delivery_fee_amount, 2)
                ]
            ],
            "buyer" => [
                "firstName" => $sale->customer_name,
                "middleName" => " ",
                "lastName" => " ",
                "birthday" => "",
                "customerSince" => "",
                "sex" => "",
                "contact" => [
                    "phone" => $sale->customer_contact_number,
                    "email" => $sale->email
                ],
                "shippingAddress" => [
                    "firstName" => $sale->customer_name,
                    "middleName" => " ",
                    "lastName" => " ",
                    "phone" => $sale->customer_contact_number,
                    "email" => $sale->email,
                    "line1" => str_replace(["\r", "\n", "'"], ' ', $sale->customer_delivery_adress),
                    "line2" => " ",
                    "city" => " ",
                    "state" => " ",
                    "zipCode" => " ",
                    "countryCode" => "PH",
                    "shippingType" => "ST"
                ],
                "billingAddress" => [
                    "line1" => str_replace(["\r", "\n", "'"], ' ', $sale->customer_delivery_adress),
                    "line2" => " ",
                    "city" => " ",
                    "state" => " ",
                    "zipCode" => " ",
                    "countryCode" => "PH"
                ]
            ],
            "items" => $items,
            "redirectUrl" => [
                "success" => route('paymaya-success') . '?id=' . $payment->id,
                "failure" => route('paymaya-failure') . '?id=' . $payment->id,
                "cancel" => route('paymaya-cancel') . '?id=' . $payment->id,
            ],
            "requestReferenceNumber" => $sale->order_number,
            "metadata" => (object)[]
        ];

        logger(json_encode($postData, JSON_PRETTY_PRINT)); // Optional for debugging
        return json_encode($postData);
    }
}
