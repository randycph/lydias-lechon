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
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
class PaymayatestController extends Controller
{

    public function pk(){
        return (config('services.paymaya.public_key'));  // test
        //return base64_encode('pk-bzhgBQYUAtCvLa0PEPQiWGHeqrDLCEAnNKi7LhJLECY'); // beta
        // return base64_encode('pk-2oMK4D8wMUbKXay0VjLHk84OiKIuTfA2YsrdSH9o844');
            
            
    }

    public function sk(){
        return (config('services.paymaya.secret_key')); //test
        //return base64_encode('sk-XU2KylKnROUoiOkxzZ4hSEGDssFqIqDtsKhjW2i6mlV');  //beta
        // return base64_encode('sk-iLyM468U8VeXEOywY2ALFyxjuQCWDGS7bWagzCDccJG');  
    
    }

    public function paymaya_url(){
        return config('services.paymaya.url'); // test
        //return 'https://pg.paymaya.com/checkout/v1/checkouts/';
      
        // return 'https://pg.paymaya.com/checkout/v1/checkouts';
    }

  

    

    public function check_paymaya($id)
    {
        $salesPayment = SalesPayment::findOrFail($id);
        $salesHeader  = $salesPayment->sales;

        // Already paid? Do nothing (idempotent)
        if ($salesPayment->status === 'PAID') {
            return true;
        }

        try {
            $res = Http::withOptions(['verify' => false])
                ->withHeaders([
                    'Authorization' => 'Basic ' . base64_encode($this->sk() . ':'), // sk:
                    'Content-Type'  => 'application/json',
                ])
                ->get($this->paymaya_url() . '/' . $salesPayment->receipt_number);

            if (!$res->successful()) {
                logger('PAYMAYA CHECK FAILED', [
                    'status' => $res->status(),
                    'body'   => $res->body(),
                ]);
                return false;
            }

            $data = $res->json();
            logger('PAYMAYA CHECK RESPONSE', $data);

            /**
             * FFICIAL SUCCESS CONDITIONS
             */
            if (
                ($data['status'] ?? null) !== 'COMPLETED' ||
                ($data['paymentStatus'] ?? null) !== 'PAYMENT_SUCCESS'
            ) {
                return false;
            }

            /**
             * CORRECT AMOUNT SOURCE (post-payment)
             */
            $paidAmount = (float)
                $data['paymentDetails']['responses']['efs']['amount']['total']['value'];

            // =========================
            // UPDATE PAYMENT RECORD
            // =========================
            $salesPayment->update([
                'amount' => $paidAmount,
                'status' => 'PAID',
            ]);

            // =========================
            // CONFIRM MAIN SALES
            // =========================
            $salesHeader->update([
                'isConfirm'       => 1,
                'confirmed_by'    => 'Customer',
                'confirmed_on'    => now(),
                'confirm_remarks' => 'Auto confirm via Maya checkout',
                'updated_at'      => $salesHeader->created_at,
            ]);

            // =========================
            // CONFIRM SUB-SALES
            // =========================
            $subSales = SalesHeader::where('parent_sales_header_id', $salesHeader->id)->get();
            foreach ($subSales as $sub) {
                $sub->update([
                    'isConfirm'       => 1,
                    'confirmed_by'    => 'Customer',
                    'confirmed_on'    => now(),
                    'confirm_remarks' => 'Auto confirm via Maya checkout',
                    'updated_at'      => $sub->created_at,
                ]);

                $sub->assign_to_production_branch($sub, 1);
            }

            // Assign production branch (main)
            $salesHeader->assign_to_production_branch($salesHeader, 1);

            // Mark coupon as used
            if ($salesHeader->discount_amount > 0) {
                CouponCart::where('sales_header_id', $salesHeader->id)
                    ->update(['status' => 1]);
            }

            return true;

        } catch (\Throwable $th) {
            logger('PAYMAYA CHECK ERROR', [
                'error' => $th->getMessage(),
            ]);
            return false;
        }
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
                        if ($payment->sales_header_id) {
                            $sale = SalesHeader::find($payment->sales_header_id);
                            $sale->isConfirm = 1;
                            $sale->confirmed_by = 'Customer';
                            $sale->confirmed_on = date('Y-m-d H:i:s');
                            $sale->confirm_remarks = 'Auto confirm via Paymaya checkout';
                            $sale->save();
                        }
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
    

    public function pay(Request $request)
    {        
        try {
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
        } catch (\Throwable $th) {
            logger('PAYMAYA ERROR:', ['error' => $th->getMessage()]);
            return Redirect::back()->withErrors(['error' => 'An error occurred while processing your payment. Please try again later.']);
        }
    }

    public function paydata($id,$amount,$checkoutId){
        

    }

    public function get_checkoutId($request, $payment)
    {
        $payload = json_decode($this->postdata($request->sales_header_id, $request->amount, $payment), true);

        $res = Http::withOptions(['verify' => false])
            ->withHeaders([
                'Authorization' => 'Basic ' . base64_encode($this->pk() . ':'),
                'Content-Type'  => 'application/json',
            ])
            ->post($this->paymaya_url(), $payload);

        return $res->json();
    }

    public function postdata($id, $amount, $payment){
        $sale = SalesHeader::find($id);

        $items = [];

        $productsAmount = 0;

        foreach ($sale->items as $i) {
            $items[] = [
                "name" => $i?->product_name ?? '',
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

        $salesHeader = SalesHeader::whereId($id)->with(['items'])->first();

        $discount = 0;

        if ($salesHeader && $salesHeader->discount_amount > 0) {
            $discount = $salesHeader->discount_amount;
            $amount = (float) $amount;
            $discount = (float) $salesHeader->discount_amount;
        }

        $amount = (float) $amount;
        $deliveryFee = (float) $sale->delivery_fee_amount ?? 0;
        $firstName = explode(' ', trim($sale->customer_name))[0] ?? $sale->customer_name;
        $lastName = trim(str_replace($firstName, '', $sale->customer_name)) ?: $firstName;
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
                "firstName" => $firstName,
                "middleName" => null,
                "lastName" => $lastName,
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
            "metadata" => [
                "sales_header_id" => (int) $salesHeader->id,
                "payment" => (string) $payment,
            ],
            "requestReferenceNumber" => $sale->order_number
        ];

        return json_encode($postData);
    }
}
