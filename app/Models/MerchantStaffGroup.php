<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MerchantStaffGroup extends Model 
{

    protected $table = 'merchant_staff_groups';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'title',
        'merchant_id',
        'created_at'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'title',
        'merchant_id',
        'created_at',
    ];


    public function merchant_staff_permission()
    {
        return $this->hasMany('App\Models\MerchantStaffPermission', 'merchant_staff_group_id');
    }

    public function merchant_staff()
    {
        return $this->hasMany('App\Models\MerchantStaff', 'merchant_staff_group_id');
    }


    public function permissions(){
        return $this->hasMany('App\Models\MerchantStaffPermission','merchant_staff_group_id');
    }

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant','merchant_id');
    }

    public function MerchantGroup()
    {
        return $this->hasMany('MerchantStaff', 'merchant_staff_group_id');
    }

    public function MerchantStaffGroup()
    {
        return $this->belongsTo('App\Models\Merchant');
    }


    public function staff(){
        return $this->hasMany('App\Models\MerchantStaff','merchant_staff_group_id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_staff_groups.id',
            'merchant_staff_groups.title',
            'merchant_staff_groups.merchant_id',
            'merchants.name_'.$langCode.' as merchant_name'
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchants','merchants.id','=','merchant_staff_groups.merchant_id')
            ->withCount('merchant_staff');
    }

}