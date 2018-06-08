<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentServiceProviders extends Model
{

    protected $table = 'payment_service_providers';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'payment_service_provider_category_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'logo',
        'status',
        'staff_id'
    ];

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

    public function payment_service_provider_category(){
        return $this->belongsTo('App\Models\PaymentServiceProviderCategories','payment_service_provider_category_id');
    }

    public function wallet(){
        return $this->morphOne('App\Models\Wallet', 'walletowner');
    }

    public function payment_services(){
        return $this->hasMany('App\Models\PaymentServices','payment_service_provider_id');
    }

}