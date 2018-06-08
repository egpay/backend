<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class TempData extends Model
{

    protected $table = 'temp_data';
    public $timestamps = true;
    public $dates = ['reviewed_at','created_at','updated_at','deleted_at'];
    
    protected $fillable = ['type','data','create_id','create_type','reviewed_id','reviewed_at'];

    public function CreatedBy(){
        return $this->morphTo('create');
    }

    protected function setDataAttribute($value){
        return $this->attributes['data'] = serialize($value);
    }

    protected function getDataAttribute($value){
        if(!is_array($value))
            return $this->attributes['data'] = unserialize($value);
        else
            return $this->attributes['data'] = $value;
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'temp_data.id',
            'temp_data.type',
            'temp_data.create_id',
            'temp_data.create_type',
            'temp_data.created_at',
            'temp_data.reviewed_id',
            'temp_data.reviewed_at',
            //\DB::raw("CASE temp_data.create_type WHEN 'App\\\\Models\\\\MerchantStaff' THEN create_id ELSE NULL END AS `merchant_staff_id`"),
            \DB::raw("CASE temp_data.create_type 
                WHEN 'App\\\\Models\\\\MerchantStaff' 
                    THEN (SELECT merchant_id FROM merchant_staff_groups WHERE merchant_staff_groups.id = (SELECT merchant_staff_group_id FROM merchant_staff WHERE merchant_staff.id = temp_data.create_id)) 
                ELSE NULL
             END AS 'merchant_id'"),
            \DB::raw("CASE temp_data.create_type 
                WHEN 'App\\\\Models\\\\MerchantStaff'
                    THEN (SELECT staff_id FROM merchants where merchants.id IN (SELECT merchant_id FROM merchant_staff_groups WHERE merchant_staff_groups.id = (SELECT merchant_staff_group_id FROM merchant_staff WHERE merchant_staff.id = temp_data.create_id))) 
                ELSE NULL
             END AS 'staff_id'"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns);
    }

    public function uploads(){
        return $this->morphMany('App\Models\Upload','model');
    }
}