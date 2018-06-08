<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestRechargeWallet extends Model
{

    protected $table = 'request_recharge_wallet';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'staff_id',
        'transfer_type',
        'from_wallet_id',
        'to_wallet_id',
        'amount',
        'status',
        'action_staff_id',
        'action_comment',
        'transaction_id'
    ];

    public function staff(){
        return $this->belongsTo('App\Models\Staff','staff_id');
    }

    public function from_wallet(){
        return $this->belongsTo('App\Models\Wallet','from_wallet_id');
    }

    public function to_wallet(){
        return $this->belongsTo('App\Models\Wallet','to_wallet_id');
    }

    public function action_staff(){
        return $this->belongsTo('App\Models\Staff','action_staff_id');
    }

    public function transaction(){
        return $this->belongsTo('App\Models\WalletTransaction','transaction_id');
    }
}