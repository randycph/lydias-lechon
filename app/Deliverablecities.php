<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deliverablecities extends Model
{
    protected $table = 'deliverable_cities';
        protected $fillable = ['name', 'rate','area',  'user_id','item_type','outside_manila'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }



    // Need to change every model
    static $oldModel;
    static $tableTitle = 'delivery location';
    static $name = 'name';
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
