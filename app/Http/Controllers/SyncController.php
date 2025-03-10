<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Logs;
use App\Models\Product;
use App\EcommerceModel\Member;
use App\EcommerceModel\Branch;

class SyncController extends Controller
{
    public function receive(Request $request)
    {   
    	$last_sync = $_GET['last_sync'];

       	$rs = Branch::whereToken($_GET['token'])->whereName($_GET['branch'])->whereCode($_GET['code'])->first();
       	if(empty($rs))
       		return 'Invalid Token';

       	$data = $this->sync($_GET['data'],$last_sync);

       	return $data;
    }

    public function sync($type,$last_sync){

    	if($type=='products')
    		return $this->products($last_sync);

    	if($type=='customers')
    		return $this->customers($last_sync);

    	return 'Invalid request';
    }

    public function products($last_sync){
    	$data = Product::where('updated_at','>=',$last_sync)->get();
    	return $data;
    }

    public function customers($last_sync){
    	$data = Member::where('updated_at','>=',$last_sync)->get();
    	return $data;
    }
}

