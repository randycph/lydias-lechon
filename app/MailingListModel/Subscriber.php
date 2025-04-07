<?php

namespace App\MailingListModel;

use App\Models\ActivityLog;
use App\Helpers\Webfocus\Setting;
use App\Mail\MailingList\CampaignMail;
use App\Models\SentCampaign;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;

class Subscriber extends Model
{
    use SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'email', 'code'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'groups_has_subscribers')->withTimestamps();;
    }

    public function email_with_name()
    {
        if (empty($this->first_name)) {
            return "{$this->email}";
        } else {
            return "{$this->email} - {$this->first_name} {$this->last_name}";
        }
    }

    public function send_campaign(Campaign $campaign)
    {
        Mail::to($this->email)->send(new CampaignMail(Setting::info(), $campaign));
//        return !Mail::failures();
    }

    public static function generate_unique_code()
    {
        $randomString = self::generate_random_string();
        $subscriber = Subscriber::where('code', $randomString)->get();
        while ($subscriber->count()) {
            $randomString = self::generate_random_string();
            $subscriber = Subscriber::where('code', $randomString)->first();
        }

        return $randomString;
    }

    private static function generate_random_string($length = 128) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }



    // Need to change every model
    static $oldModel;
    static $tableTitle = 'subscriber';
    static $name = 'email';
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
