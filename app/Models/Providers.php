<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Providers extends Model 
{

    protected $table = 'service_providers';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('name_ar', 'name_en', 'description_ar', 'description_en', 'address', 'mobile', 'mobile2', 'phone1', 'phone2', 'fax1', 'fax2', 'status', 'staff_id');

    public function service_provider_id()
    {
        return $this->hasMany('ProviderContract', 'service_provider_id');
    }

    public function services()
    {
        return $this->hasMany('ProviderService', 'service_provider_id');
    }

    public function walletowner()
    {
        return $this->morphOne('App\Models\Providers');
    }

    public function loyaltywalletowner()
    {
        return $this->morphOne('App\Models\Providers');
    }

}