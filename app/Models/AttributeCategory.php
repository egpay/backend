<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeCategory extends Model
{
    protected $fillable = ['name_ar','name_en','description_ar','description_en'];
    public $timestamps = true;


    public function attributes(){
        return $this->hasMany('App\Models\Attribute','attribute_category_id');
    }
}
