<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentServiceAPIs extends Model{

    protected $table = 'payment_service_apis';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'payment_service_id',
        'service_type',
        'name',
        'description',
        'external_system_id',
        'price_type',
        'service_value',
        'service_value_list',
        'min_value',
        'max_value',
        'commission_type',
        'commission_value_type',
        'fixed_commission',
        'default_commission',
        'from_commission',
        'to_commission',
        'staff_id'
    ];

    public function payment_service(){
        return $this->belongsTo('App\Models\PaymentServices','payment_service_id');
    }

    public function payment_service_api_parameters(){
        return $this->hasMany('App\Models\PaymentServiceAPIParameters','payment_services_api_id');
    }


    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'payment_service_apis.id',
            'payment_service_apis.payment_service_id',
            'payment_service_apis.service_type',
            'payment_services.request_amount_input',
            'payment_service_apis.external_system_id',
            'payment_services.name_'.$langCode.' as service_name'
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('payment_services','payment_services.id','=','payment_service_apis.payment_service_id')
            ;
    }

}