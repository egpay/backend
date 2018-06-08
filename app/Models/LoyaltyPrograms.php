<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LoyaltyPrograms extends Model{

    protected $table = 'loyalty_programs';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'type',
        'transaction_type',
        'status',
        'pay_type',
        'list',
        'owner',
        'staff_id'
    ];
    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'type',
        'transaction_type',
        'status',
        'pay_type',
        'list',
        'owner',
        'staff_id'
    ];

    public function getListAttribute($value){
        return @unserialize($value);
    }

    public function setListAttribute($value){
        $this->attributes['list'] = @serialize($value);
    }

    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }

}