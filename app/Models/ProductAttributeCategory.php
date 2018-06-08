<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeCategory extends Model
{
    protected $table = 'attribute_categories';
    protected $fillable = ['name_ar','name_en','description_ar','description_en'];

    public function attribute(){
        return $this->hasMany('App\Models\Attribute','attribute_category_id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'attribute_categories.id',
            'attribute_categories.name_ar',
            'attribute_categories.name_en',
            'attribute_categories.description_ar',
            'attribute_categories.description_en',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)->distinct()
            ;
    }
}
