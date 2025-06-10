<?php

namespace App\Http\Controllers\EcommerceControllers;

use App\EcommerceModel\CouponCart;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\EcommerceModel\SalesHeader;
use App\EcommerceModel\SalesDetail;
use App\EcommerceModel\SalesPayment;
use Illuminate\Support\Facades\Auth;
Use Illuminate\Support\Facades\Redirect;

class PaymayaController extends Controller
{

    public function pk(){
        return base64_encode('pk-eo4sL393CWU5KmveJUaW8V730TTei2zY8zE4dHJDxkF');  // test
        //return base64_encode('pk-bzhgBQYUAtCvLa0PEPQiWGHeqrDLCEAnNKi7LhJLECY'); // beta
        // return base64_encode('pk-2oMK4D8wMUbKXay0VjLHk84OiKIuTfA2YsrdSH9o844');
            
            
    }

    public function sk(){
        return base64_encode('sk-KfmfLJXFdV5t1inYN8lIOwSrueC1G27SCAklBqYCdrU'); //test
        //return base64_encode('sk-XU2KylKnROUoiOkxzZ4hSEGDssFqIqDtsKhjW2i6mlV');  //beta
        // return base64_encode('sk-iLyM468U8VeXEOywY2ALFyxjuQCWDGS7bWagzCDccJG');  
    
    }

    public function paymaya_url(){
        return 'https://pg-sandbox.paymaya.com/checkout/v1/checkouts'; // test
        //return 'https://pg.paymaya.com/checkout/v1/checkouts/';
      
        // return 'https://pg.paymaya.com/checkout/v1/checkouts';
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

            if ($sales->sales->discount_amount && $sales->sales->discount_amount > 0) {
                CouponCart::where('sales_header_id', $sales->sales->id)->update([
                    'status' => 1
                ]);
            }

            return true;
        }

        return false;

         
    }

    public function success(){        
        $tag = $this->check_paymaya($_GET['id']);   
        $order = SalesPayment::find($_GET['id']);
        if(Auth::guest())
            return redirect()->route('confirmation',['id' => $order->sales->HashOrderNumber, 'payment_successful' => 'yes', 'order_no' => $order->sales->order_number]);
        else
            return redirect()->route('order-history',['payment_successful' => 'yes', 'order_no' => $order->sales->order_number]);
    }

    public function failure(){
        $update = SalesPayment::whereId($_GET['id'])->update([
            'status' => 'CANCELLED'
        ]);
        $delete = SalesPayment::whereId($_GET['id'])->delete();
     
        $order = SalesPayment::whereId($_GET['id'])->withTrashed()->first();
        if(Auth::guest())
            return redirect()->route('confirmation',['id' => $order->sales->HashOrderNumber, 'order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
        else
            return redirect()->route('order-history',['order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
    }
    public function cancel(){
        $update = SalesPayment::whereId($_GET['id'])->update([
            'status' => 'CANCELLED'
        ]);
        $delete = SalesPayment::whereId($_GET['id'])->delete();
        
        $order = SalesPayment::whereId($_GET['id'])->withTrashed()->first();
        if(Auth::guest())
            return redirect()->route('confirmation',['id' => $order->sales->HashOrderNumber, 'order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
        else
            return redirect()->route('order-history',['order_cancelled' => 'cancelled', 'order_no' => $order->sales->order_number]);
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
        
        // dd($request->all());
        $sales = SalesHeader::find($request->sales_header_id); 

        if ($sales && $sales->items && count($sales->items) == 0) {
            return Redirect::back()->withErrors(['error' => 'No items found in the sales order.']);
        }

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

        $update_payment = $payment->update([
            'receipt_number' => $checkoutId['checkoutId']
        ]);
        
        return Redirect::to($checkoutId['redirectUrl']);
    }

    public function paydata($id,$amount,$checkoutId){
        

    }

    public function get_checkoutId($request, $payment)
    {
        $data = $this->postdata($request->sales_header_id, $request->amount, $payment);

        $context = stream_context_create([
            'http' => [
                'method'  => 'POST',
                'header'  => "Authorization: Basic " . $this->pk() . "\r\n" .
                            "Content-Type: application/json\r\n",
                'content' => $data,
                'ignore_errors' => true, // capture errors too
            ]
        ]);

        $response = file_get_contents($this->paymaya_url(), false, $context);

        if ($response === false || strpos($http_response_header[0], '200') === false) {
            logger('PAYMAYA 400 DEBUG HEADERS:', $http_response_header);
            logger('PAYMAYA 400 DEBUG RESPONSE:', [$response]);
            dd('PayMaya Error', $http_response_header, $response);
        }

        return json_decode($response, true);
    }

    public function postdata($id,$amount, $payment){
        $sale = SalesHeader::find($id);

        $items = [];

        $productsAmount = 0;

        foreach ($sale->items as $i) {
            $items[] = [
                "name" => $i->product_name,
                "quantity" => (int) $i->qty,
                "code" => (string) $i->product_id,
                "description" => "",
                "amount" => [
                    "value" => (float) $i->gross_amount,
                    "details" => [
                        "discount" => 0,
                        "serviceCharge" => 0,
                        "shippingFee" => 0,
                        "tax" => 0,
                        "subtotal" => (float) $i->gross_amount,
                    ]
                ],
                "totalAmount" => [
                    "value" => (float) $i->gross_amount,
                    "details" => [
                        "discount" => 0,
                        "serviceCharge" => 0,
                        "shippingFee" => 0,
                        "tax" => 0,
                        "subtotal" => (float) $i->gross_amount,
                    ]
                ]
            ];

            $productsAmount += (float) $i->gross_amount;
        }

        $discount = 0;

        if ($sale && $sale->discount_amount > 0) {
            $discount = $sale->discount_amount;
            $amount = (float) $amount;
            $discount = (float) $sale->discount_amount;
        }

        $amount = (float) $amount;
        $deliveryFee = (float) $sale->delivery_fee_amount ?? 0;
        $postData = [
            "totalAmount" => [
                "value" => (float) $amount,
                "currency" => "PHP",
                "details" => [
                    "discount" => $discount,
                    "serviceCharge" => 0,
                    "shippingFee" => $deliveryFee,
                    "tax" => 0,
                    "subtotal" => $productsAmount
                ]
            ],
            "buyer" => [
                "firstName" => $sale->customer_name,
                "middleName" => null,
                "lastName" => null,
                "birthday" => "",
                "customerSince" => "",
                "sex" => "",
                "contact" => [
                    "phone" => $sale->customer_contact_number,
                    "email" => $sale->email
                ],
                "shippingAddress" => [
                    "firstName" => $sale->customer_name,
                    "middleName" => null,
                    "lastName" => null,
                    "phone" => $sale->customer_contact_number,
                    "email" => $sale->email,
                    "line1" => trim(str_replace(["\r", "\n", "'"], ' ', $sale->customer_delivery_adress)),
                    "line2" => null,
                    "city" => "Metro Manila",
                    "state" => "NCR",
                    "zipCode" => "1700",
                    "countryCode" => "PH",
                    "shippingType" => "ST"
                ],
                "billingAddress" => [
                    "line1" => trim(str_replace(["\r", "\n", "'"], ' ', $sale->customer_delivery_adress)),
                    "line2" => null,
                    "city" => "Metro Manila",
                    "state" => "NCR",
                    "zipCode" => "1700",
                    "countryCode" => "PH"
                ]
            ],
            "items" => $items,
            "redirectUrl" => [
                "success" => route('paymaya-success').'?id='.$payment->id,
                "failure" => route('paymaya-failure').'?id='.$payment->id,
                "cancel" => route('paymaya-cancel').'?id='.$payment->id
            ],
            "requestReferenceNumber" => $sale->order_number,
            "metadata" => new \stdClass()
        ];

        return json_encode($postData);
    }
}
