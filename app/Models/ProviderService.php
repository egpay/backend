<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderService extends Model 
{

    protected $table = 'service_provider_services';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('service_provider_id', 'name_ar', 'name_en', 'description_ar', 'description_en', 'type');

    public function ProviderServices()
    {
        return $this->belongsTo('App\Models\Providers', 'id');
    }

}