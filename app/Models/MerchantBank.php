<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MerchantBank extends Model
{

    protected $table = 'merchant_banks';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('merchant_id', 'name', 'account_number','bank_id');


    public function bank(){
        return $this->belongsTo('App\Models\Bank','bank_id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_banks.id',
            "merchant_banks.name",
            "merchants.name_$langCode as merchant_name",
            "banks.name_$langCode as bank_name",
            "banks.logo"
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('banks','banks.id','=','merchant_banks.bank_id')
            ->join('merchants','merchants.id','=','merchant_banks.merchant_id');
    }
    
}