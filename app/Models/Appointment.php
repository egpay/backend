<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{

    protected $table = 'appointment';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'model_type',
        'model_id',
        'appointment_date_time',
        'description',
        'status',
    ];



    public function appointmentStatus(){
        return $this->hasMany('App\Models\AppointmentStatus','appointment_id');
    }

    public function model(){
        return $this->morphTo();
    }

}