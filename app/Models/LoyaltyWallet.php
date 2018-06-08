<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LoyaltyWallet extends Model{

    protected $table = 'loyalty_wallet';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'walletowner_id',
        'walletowner_type',
        'balance'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'walletowner_id',
        'walletowner_type',
        'balance',
    ];

    public function transactionFrom(){
        return $this->hasMany('App\Models\LoyaltyWalletTransaction', 'from_id');
    }

    public function transactionTo(){
        return $this->hasMany('App\Models\LoyaltyWalletTransaction', 'to_id');
    }

    public function allTransaction(){
        return LoyaltyWalletTransaction::where(function($query){
            $query->where('from_id',$this->id)
                ->orWhere('to_id',$this->id);
        })->with(['fromWallet','toWallet','model']);
    }

    public function walletowner()
    {
        return $this->morphTo();
    }


}