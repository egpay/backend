<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSDK extends Model{

    protected $table = 'payment_sdk';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'adapter_name',
        'name',
        'description',
        'address',
        'logo',
        'area_id',
        'staff_id'
    ];


   public function services(){
       return $this->hasMany('App\Models\PaymentServices','payment_sdk_id');
   }


   public function staff(){
       return $this->belongsTo('App\Models\Staff','staff_id');
   }



}