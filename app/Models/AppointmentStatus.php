<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppointmentStatus extends Model
{

    protected $table = 'appointment_status';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'appointment_id',
        'model_type',
        'model_id',
        'status',
        'comment'
    ];



    public function appointment(){
        return $this->belongsTo('App\Models\Appointment','appointment_id');
    }


    public function model(){
        return $this->morphTo();
    }



}