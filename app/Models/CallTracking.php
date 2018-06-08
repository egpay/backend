<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\User;

class CallTracking extends Model
{

    protected $table = 'call_tracking';
    public $timestamps = true;

    use LogsActivity;

    protected $dates = ['calltime'];
    protected $fillable = [
        'type',
        'phone_number',
        'calltime',
        'caller_name',
        'details',
        'staff_id',
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'type',
        'phone_number',
        'calltime',
        'caller_name',
        'details',
        'staff_id',
    ];


    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'call_tracking.id',
            'call_tracking.type',
            'call_tracking.phone_number',
            "call_tracking.calltime",
            "call_tracking.caller_name",
            "call_tracking.details",
            "call_tracking.created_at",
            "call_tracking.updated_at",
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ;
    }
}