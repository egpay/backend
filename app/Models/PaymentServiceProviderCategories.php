<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentServiceProviderCategories extends Model
{

    protected $table = 'payment_service_provider_categories';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'icon',
        'status',
        'staff_id',
        'sort_by'
    ];


    public function payment_service_providers(){
        return $this->hasMany('App\Models\PaymentServiceProviders','payment_service_provider_category_id');
    }

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }

}