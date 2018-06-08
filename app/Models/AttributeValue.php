<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    protected $fillable = ['attribute_id','text_ar','text_en','is_default'];
    public $timestamps = true;

    public function attribute(){
        return $this->belongsTo('App\Models\Attribute','attribute_id');
    }
}
