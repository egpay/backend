<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class CommissionList extends Model{

    use LogsActivity;
    protected $table = 'commission_list';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'name',
        'description',
        'commission_type',
        'condition_data',
        'staff_id'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'description',
        'commission_type',
        'condition_data',
        'staff_id',
    ];


    public function setConditionDataAttribute($value){
        if(!is_array($value)){
            $this->attributes['condition_data'] = [];
        }else{
            $this->attributes['condition_data'] = @serialize($value);
        }
    }

    public function getConditionDataAttribute($value){
        return @unserialize($value);
    }



}