<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentServiceAPIParameters extends Model{

    protected $table = 'payment_service_api_parameters';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'external_system_id',
        'payment_services_api_id',
        'name_ar',
        'name_en',
        'position',
        'visible',
        'required',
        'type',
        'is_client_id',
        'default_value',
        'min_length',
        'max_length',
        'staff_id'
    ];

    public function payment_service_apis(){
        return $this->belongsTo('App\Models\PaymentServiceAPIs','payment_services_api_id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'payment_service_api_parameters.id',
            'payment_service_api_parameters.external_system_id',
            'payment_service_api_parameters.payment_services_api_id',
            'payment_service_api_parameters.name_'.$langCode.' as name',
            'payment_service_api_parameters.position',
            'payment_service_api_parameters.visible',
            'payment_service_api_parameters.required',
            'payment_service_api_parameters.type',
            'payment_service_api_parameters.is_client_id',
            'payment_service_api_parameters.default_value',
            'payment_service_api_parameters.min_length',
            'payment_service_api_parameters.max_length',

        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->whereNull('payment_service_api_parameters.deleted_at')

            ;
    }

}