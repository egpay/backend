<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RechargeListNumbers extends Model
{
    public $timestamps = true;
    protected $table = 'recharge_list_numbers';

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $fillable = [
        'recharge_list_id',
        'service',
        'mobile',
        'amount',
        'status'
    ];

    public function recharge_list(){
        return $this->belongsTo('App\Models\RechargeList','recharge_list_id');
    }
}