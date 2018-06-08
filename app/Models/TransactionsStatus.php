<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionsStatus extends Model
{
    protected $table = 'transactions_status';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'user_id',
        'user_type',
        'transaction_id',
        'status',
        'comment'
    ];


    public function transactions(){
        return  $this->belongsTo('App\Models\WalletTransaction','transaction_id');
    }

    public function user(){
        return $this->morphTo();
    }


}