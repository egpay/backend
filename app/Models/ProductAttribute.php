<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    protected $fillable = ['product_id','attribute_id','required','selected_attribute_value','stock','quantity','plus_price'];

    public function product(){
        return $this->belongsTo('App\Models\MerchantProduct','product_id');
    }


    public function attribute(){
        return $this->belongsTo('App\Models\Attribute','attribute_id');
    }

    public function attrValues(){
        return $this->belongsTo('App\Models\AttributeValue','selected_attribute_value','id');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'product_attributes.id',
            'product_attributes.product_id',
            'product_attributes.attribute_id',
            'product_attributes.required',
            'product_attributes.selected_attribute_value',
            'attribute_values.text_'.$langCode.' as value_text',
            'product_attributes.stock',
            'product_attributes.quantity',
            'product_attributes.plus_price',
            'attributes.name_'.$langCode.' as name',
            'attributes.type',
            'attributes.description_'.$langCode.' as description',
            'attribute_categories.name_'.$langCode.' as category_name',
            'attribute_categories.description_'.$langCode.' as category_description',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)->distinct()
            ->join('attributes','attributes.id','=','product_attributes.attribute_id')
            ->join('attribute_categories','attribute_categories.id','=','attributes.attribute_category_id')
            ->leftJoin('attribute_values','attribute_values.id','=','product_attributes.selected_attribute_value')
            ;
    }
}
