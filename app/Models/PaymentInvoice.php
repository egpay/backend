<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class PaymentInvoice extends Model
{

    protected $table = 'payment_invoice';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'payment_transaction_id',
        'creatable_id',
        'creatable_type',
        'total',
        'total_amount',
        'status',
        'wallet_settlement_id',
        'wallet_settlement_data'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'payment_transaction_id',
        'creatable_id',
        'creatable_type',
        'total',
        'total_amount',
        'status',
        'wallet_settlement_id',
        'wallet_settlement_data',
    ];

    public function creatable(){
        return $this->morphTo();
    }

    public function payment_services(){
        return $this->belongsTo('App\Models\PaymentTransactions','payment_transaction_id');
    }

    public function payment_transaction(){
        return $this->belongsTo('App\Models\PaymentTransactions','payment_transaction_id');
    }

    public function wallet_transaction(){
        return $this->morphOne('App\Models\WalletTransaction','model');
    }


    public function setWalletSettlementDataAttribute($value)
    {
        $this->attributes['wallet_settlement_data'] = @serialize($value);
    }

    public function getWalletSettlementDataAttribute($value){
        $value = @unserialize($value);
        if(is_array($value)){
            return $value;
        }else{
            return [];
        }
    }




}