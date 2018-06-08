<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RechargeList extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $table = 'recharge_list';
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'name',
        'merchant_id',
        'status',
        'xls_path',
        'cron_jobs',
        'system_run'
    ];

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant','merchant_id');
    }

    public function numbers(){
        return $this->hasMany('App\Models\RechargeListNumbers','recharge_list_id');
    }

}