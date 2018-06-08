<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentServices extends Model
{

    protected $table = 'payment_services';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'payment_sdk_id',
        'payment_service_provider_id',
        'payment_output_id',
        'commission_list_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'request_amount_input',
        'status',
        'icon',
        'staff_id'
    ];


    public function commission_list(){
        return $this->belongsTo('App\Models\CommissionList','commission_list_id');
    }

    public function payment_output(){
        return $this->belongsTo('App\Models\PaymentOutput','payment_output_id');
    }

    public function payment_service_apis(){
        return $this->hasMany('App\Models\PaymentServiceAPIs','payment_service_id');
    }

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public function payment_sdk(){
        return $this->belongsTo('App\Models\PaymentSDK','payment_sdk_id');
    }

    public function payment_service_provider(){
        return $this->belongsTo('App\Models\PaymentServiceProviders','payment_service_provider_id');
    }

    public function payment_invoice(){
        return $this->hasMany('App\Models\PaymentInvoice','payment_services_id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'payment_services.id',
            'payment_services.payment_service_provider_id',
            'payment_services.name_'.$langCode.' AS name',
            'payment_services.description_'.$langCode.' AS description',
            //'payment_services.status',
            'payment_services.icon',

            'payment_service_providers.id as provider_id',
            'payment_service_providers.payment_service_provider_category_id',
            'payment_service_providers.name_'.$langCode.' AS provider_name',
            'payment_service_providers.description_'.$langCode.' AS provider_description',
            //'payment_service_providers.status as provider_status',
            'payment_service_providers.logo as provider_logo',

            'payment_service_provider_categories.id as category_id',
            'payment_service_provider_categories.name_'.$langCode.' AS category_name',
            'payment_service_provider_categories.description_'.$langCode.' AS category_description',
            //'payment_service_provider_categories.status as category_status',
            'payment_service_provider_categories.icon as category_icon',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->whereNull('payment_services.deleted_at')
            ->where('payment_services.status','=','active')
            ->where('payment_service_providers.status','=','active')
            ->where('payment_service_provider_categories.status','=','active')
            ->join('payment_service_providers','payment_service_providers.id','=','payment_services.payment_service_provider_id')
            ->join('payment_service_provider_categories','payment_service_provider_categories.id','=','payment_service_providers.payment_service_provider_category_id')
            ;
    }


}