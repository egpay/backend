<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderContract extends Model 
{

    protected $table = 'service_provider_contract';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('service_provider_id', 'staff_id');

    public function ProviderContracts()
    {
        return $this->belongsTo('App\Models\Providers', 'id');
    }

}