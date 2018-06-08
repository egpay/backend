<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Wallet extends Model 
{

    protected $table = 'wallet';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['deleted_at','created_at','updated_at'];
    protected $fillable = [
        'type',
        'walletowner_id',
        'walletowner_type',
        'balance'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'type',
        'walletowner_id',
        'walletowner_type',
        'balance',
    ];

    public function transactionFrom(){
        return $this->hasMany('App\Models\WalletTransaction', 'from_id');
    }

    public function transactionTo(){
        return $this->hasMany('App\Models\WalletTransaction', 'to_id');
    }

    public function allTransaction(){
        return WalletTransaction::where(function($query){
            $query->where('from_id',$this->id)
                ->orWhere('to_id',$this->id);
        })->with([
            'fromWallet'=>function($sql){
            $sql->with('walletowner');
        },
            'toWallet'=>function($sql){
                $sql->with('walletowner');
            },
            'model']);
    }

    public function walletowner()
    {
        return $this->morphTo();
    }


}