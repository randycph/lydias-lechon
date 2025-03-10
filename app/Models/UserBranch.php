<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UserBranch extends Model
{
    public $table = 'user_branches';

    protected $fillable = [ 'user_id', 'branch_id',];

    public static function accessBranch(){
        
        if(Auth::user()->role_id == 1){
        	$branches = \App\EcommerceModel\Branch::get(['id as branch_id', 'name']);   
            //$branches = UserBranch::distinct()->get(['branch_id']);    	
        }
        else{
        	$branches = UserBranch::where('user_id',Auth::id())->get();
        }
        
        return $branches;
    }

    public function branch(){
        return $this->belongsTo('App\EcommerceModel\Branch')->withTrashed();
    }
}
