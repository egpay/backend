<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class WalletSettlement extends Model
{

    protected $table = 'wallet_settlement';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'staff_id',
        'wallet_id',
        'agent_wallet_id',
        'status',
        'system_commission',
        'merchant_commission',
        'agent_commission',
        'from_date_time',
        'to_date_time',
        'num_success',
        'num_error'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'staff_id',
        'wallet_id',
        'agent_wallet_id',
        'status',
        'system_commission',
        'merchant_commission',
        'agent_commission',
        'from_date_time',
        'to_date_time',
        'num_success',
        'num_error',
    ];


    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }

    public function wallet(){
        return $this->belongsTo('App\Models\Wallet','wallet_id');
    }

    public function agent_wallet(){
        return $this->belongsTo('App\Models\Wallet','agent_wallet_id');
    }

    public function transactions(){
        return $this->morphOne('App\Models\WalletTransaction','model');
    }


    public function payment_invoice(){
        return $this->hasMany('App\Models\PaymentInvoice','wallet_settlement_id');
    }


}