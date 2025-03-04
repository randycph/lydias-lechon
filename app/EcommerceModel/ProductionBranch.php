<?php

namespace App\EcommerceModel;

use App\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionBranch extends Model
{
    use SoftDeletes;

    protected $table = 'production_branches';
    protected $fillable = ['name', 'address_region', 'address_province', 'address_city', 'address_street', 'address_zip', 'contact_tel', 'contact_mobile', 'contact_person', 'created_by'];

    public static function branches()
    {
    	$branches = ProductionBranch::orderBy('name','asc')->get();

    	$output = "";
    	foreach ($branches as $branch) {
    		$output .= '<option value="'.$branch->name.'">'.$branch->name.'</option>';
    	}

    	return $output;
    }

    // Need to change every model
    static $oldModel;
    static $tableTitle = 'production branch';
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
