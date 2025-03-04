<?php

namespace App\EcommerceModel;

use App\ActivityLog;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobOrder extends Model
{
    use SoftDeletes;

    protected $table = 'job_orders';
    protected $fillable = ['user_id', 'jo_number', 'sales_number', 'sales_detail_id', 'order_source','product_id','product_name','product_size','product_weight', 'product_category','customer_name', 'customer_mobile_number','customer_tel_number', 'customer_address', 'customer_delivery_adress', 'delivery_tracking_number', 'delivery_fee_amount','delivery_method', 'date_needed', 'pickup_branch',
        'delivery_status', 'status', 'jo_category', 'jo_order_type', 'qty', 'remarks', 'price', 'paella_qty', 'paella_price'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo('App\Product')->withTrashed();
    }

    public function prodOrder()
    {
        return $this->hasOne('App\EcommerceModel\ProductionOrder','joborder_id');
    }

    public function receiver()
    {
        return $this->belongsTo('App\EcommerceModel\Branch','pickup_branch')->where('pickup_branch','>',0);
    }

    public function sales_detail()
    {
        return $this->belongsTo('App\EcommerceModel\SalesDetail','sales_detail_id');
    }

    public function deliveries(){
        return $this->hasMany('App\EcommerceModel\DeliveryStatus','order_id');
    }



    // Need to change every model
    static $oldModel;
    static $tableTitle = 'job order';
    static $name = 'jo_number';
    // END Need to change every model

    public static function boot()
    {
        parent::boot();

        self::created(function($model) {
            $name = $model[self::$name];
            ActivityLog::create([
                'created_by' => auth()->id(),
                'activity_type' => 'insert',
                'dashboard_activity' => 'created a new '. self::$tableTitle,
                'activity_desc' => 'created the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => '',
                'new_value' => $name,
                'reference' => $model->id
            ]);
        });

        self::updating(function($model) {
            self::$oldModel = $model->fresh();
        });

        self::updated(function($model) {
            $name = $model[self::$name];
            $unrelatedFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
            $oldModel = self::$oldModel->toArray();
            foreach ($oldModel as $fieldName => $value) {
                if (in_array($fieldName, $unrelatedFields)) {
                    continue;
                }

                $oldValue = $model[$fieldName];
                if ($oldValue != $value) {
                    $fieldNames = implode(' ', explode('_', $fieldName));
                    ActivityLog::create([
                        'created_by' => auth()->id(),
                        'activity_type' => 'update',
                        'dashboard_activity' => 'updated the '. self::$tableTitle .' '. $fieldNames,
                        'activity_desc' => 'updated the '. self::$tableTitle .' '. $fieldNames .'of '. $name .' from '. $oldValue .' to '. $value,
                        'activity_date' => date("Y-m-d H:i:s"),
                        'db_table' => $model->getTable(),
                        'old_value' => $oldValue,
                        'new_value' => $value,
                        'reference' => $model->id
                    ]);
                }
            }
        });

        self::deleted(function($model){
            $name = $model[self::$name];
            ActivityLog::create([
                'created_by' => auth()->id(),
                'activity_type' => 'delete',
                'dashboard_activity' => 'deleted a '. self::$tableTitle,
                'activity_desc' => 'deleted the '. self::$tableTitle .' '. $name,
                'activity_date' => date("Y-m-d H:i:s"),
                'db_table' => $model->getTable(),
                'old_value' => $name,
                'new_value' => '',
                'reference' => $model->id
            ]);
        });
    }
}
