<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class PaymentTransactions extends Model
{

    protected $table = 'payment_transactions';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'model_id',
        'model_type',
        'service_type',
        'external_system_id',
        'payment_services_id',
        'amount',
        'total_amount',
        'request_map',
        'response_type',
        'response',
        'is_paid'
    ];



    public function setRequestMapAttribute($value){
        if(!is_array($value) || empty($value)){
            $value = [];
        }
        $this->attributes['request_map'] = @serialize($value);
    }


    public function getRequestMapAttribute($value){
        return @unserialize($value);
    }


    public function setResponseAttribute($value){
        if(!is_array($value) || empty($value)){
            $value = [];
        }
        $this->attributes['response'] = @serialize($value);
    }

    public function getResponseAttribute($value){
        return @unserialize($value);
    }


    public function payment_services(){
        return $this->belongsTo('App\Models\PaymentServices','payment_services_id');
    }

    public function model(){
        return $this->morphTo();
    }


    /**
     * @param string language ['ar','en']
     * @param array Any addition Columns
     * @return mixed of payment services with count
     */
    public static function serviceList($langCode, array $additionColumn = []){
        $columns = [
            DB::raw('COUNT(payment_services.id) as service_count'),
            'payment_services.id',
            'payment_services.name_'.$langCode.' AS name',
            'payment_service_providers.name_'.$langCode.' AS provider_name',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('payment_invoice','payment_invoice.payment_transaction_id','=','payment_transactions.id')
            ->join('payment_services','payment_services.id','=','payment_transactions.payment_services_id')
            ->join('payment_service_providers','payment_services.payment_service_provider_id','=','payment_service_providers.id')
            ->groupBy('payment_services.id')
            ;
    }
}