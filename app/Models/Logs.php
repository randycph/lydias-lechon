<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Logs extends Model
{
    protected $table = 'view_activity_logs';

    public static function logType($type){

        switch($type){
            case 'update_content':
                return 'update';
            break;

            case 'insert_access':
                return 'set permission';
            break;

            case 'remove_access':
                return 'remove permission';
            break;

            default:
                return $type;
        }
    }

    public static function getLogs($type,$id){
        $log = Logs::where('id',$id)->first();

        switch($type){
            case 'update':
                return Str::limit(str_replace(['<p>','</p>'],'',$log->activity_desc), 100, $end ='...');
                break;

            case 'update_content':
                return $log->activity_desc.' '.Str::limit(str_replace(['<p>','</p>'],'',$log->old_value), 60, $end = '...').' to '.Str::limit(str_replace(['<p>','</p>'],'',$log->new_value), 60, $end = '...');
                break;
            
            case 'delete' || 'restore':
                return $log->activity_desc;
                break;
            
            case 'insert':
                return $log->activity_desc;
                break;

            case 'insert_access' || 'update_access' || 'remove_access':
                return $log->activity_desc;
                break;
        }
    }

}
