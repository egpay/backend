<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MainWallets extends Model
{
    public $timestamps = true;
    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'unique_name',
        'name',
        'description',
        'transfer_in',
        'transfer_out'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'unique_name',
        'name',
        'description',
        'transfer_in',
        'transfer_out',
    ];

    public function wallet(){
        return $this->morphMany('App\Models\Wallet','walletowner');
    }

}