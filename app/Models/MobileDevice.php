<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobileDevice extends Model
{

    protected $table = 'mobile_devices';
    public $timestamps = true;

    protected $fillable = ['user_id','user_type','device_token','device_version','device_model','device_serial','updated_at'];


    public function user(){
        return $this->morphTo();
    }

}