<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantPlan extends Model
{

    protected $table = 'merchant_plans';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = array('title', 'description', 'months', 'amount', 'staff_id', 'type');

    public function merchant_contract()
    {
        return $this->hasMany('App\Models\MerchantContract', 'plan_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function getTypeAttribute($value){
        $value = @unserialize($value);
        if($value !== false){
            return $value;
        }
        return [];
    }

}