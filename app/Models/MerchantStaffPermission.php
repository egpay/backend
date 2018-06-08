<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MerchantStaffPermission extends Model 
{

    protected $table = 'merchant_staff_permissions';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('merchant_staff_group_id','route_name');
    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'merchant_staff_group_id',
        'route_name',
    ];

    public function merchant_staff_group()
    {
        return $this->belongsTo('App\Models\MerchantStaffGroup');
    }

}