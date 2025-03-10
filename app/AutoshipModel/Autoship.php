<?php

namespace App\AutoshipModel;

use App\Models\User;
use \Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class Autoship extends Model
{   

    protected $table = 'ecommerce_autoship_schedule';
    protected $fillable = ['id', 'user_id', 'sales_id', 'delivery_date', 'status', 'schedule_type'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->belongsTo('\App\EcommerceModel\SalesHeader','sales_id');
    }

    public function details()
    {
        return $this->hasMany('App\AutoshipModel\AutoshipDetail','autoship_header_id');
    }

    // public static function auto_cancel_autoship_if_unpaid($id,$scheduled_date)
    // {   
    //     $data =  Autoship::where('id',$id)->first();

    //     $date = new Carbon($scheduled_date);
    //     $date->addDays(7);

    //     if($date < Carbon::today()){
    //         Autoship::where('id',$id)->update([
    //             'status' => 'CANCELLED'
    //         ]);

    //         return '<td><span class="badge badge-warning">CANCELLED</span></td><td class="text-right"><div class="btn-group pd-r-5" role="group" aria-label="Basic example">
    //         <button type="button" class="btn btn-secondary btn-xs" data-toggle="collapse" data-target="#autoship-details_'.$data->id.'" class="accordion-toggle">View</button></div></td>';

    //     } else {
    //         return '<td><span class="badge badge-secondary">PENDING</span></td><td class="text-right"><div class="btn-group pd-r-5" role="group" aria-label="Basic example">
    //             <button type="button" class="btn btn-secondary btn-xs" data-toggle="collapse" data-target="#autoship-details_'.$data->id.'" class="accordion-toggle">View</button><a href="'.route('autoship.edit', $data->id).'" class="btn btn-secondary btn-xs">Edit</a>
    //             <a href="'.route('autoship.payment-process', $data->id).'" class="btn btn-secondary btn-xs">Pay</a>
    //         </div></td>';
    //     }
        
    // }

    public static function next_processing_cycle($id,$sales_id)
    {
        $data = Autoship::where('id', '>', $id)->orderBy('id')->first();

        return date('d F Y',strtotime($data->delivery_date));

    }
        

}
