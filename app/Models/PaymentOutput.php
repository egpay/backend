<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentOutput extends Model{

    protected $table = 'payment_output';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'id',
        'name',
        'parameters',
        'staff_id'
    ];

    public function getParametersAttribute($value){
        return @unserialize($value);
    }

    public function setParametersAttribute($value){
        $this->attributes['parameters'] = @serialize($value);
    }


}