<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use DB;
class IpayController extends Controller
{

    public function receive_data()
    {      
        
        logger($_REQUEST);
        // $data['merchantcode'] = $request->MerchantCode;
        // $data['paymentid'] = $request->PaymentId;
        // $data['refno'] = $request->RefNo;
        // $data['amount'] = $request->Amount;
        // $data['ecurrency'] = $request->Currency;
        // $data['remark'] = $request->Remark;
        // $data['transid'] = $request->TransId;
        // $data['authcode'] = $request->AuthCode;
        // $data['estatus'] = $request->Status;
        // $data['errdesc'] = $request->ErrDesc;
        // $data['signature'] = $request->Signature;

        // dd($request);
        
    }

    
}
