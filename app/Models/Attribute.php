<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['attribute_category_id','type','name_ar','name_en','description_ar','description_en','multi_lang'];
    public $timestamps = true;


    /*
     * start of attributes
     */

    /*
    protected function setOptionsAttribute($value){
        if(is_array($value))
            return $this->attributes['options'] = serialize($value);
        else
            return $this->attributes['options'] = $value;
    }

    protected function getOptionsAttribute($value){
        if(!is_array($value) && strpos($value,':')==1)
            return $this->attributes['options'] = unserialize($value);
        else
            return $this->attributes['options'] = $value;
    }

    protected function getDefaultValueAttribute($value){
        if(!is_array($value) && strpos($value,':')==1)
            return $this->attributes['default_value'] = unserialize($value);
        else
            return $this->attributes['default_value'] = $value;
    }


    public function getDefault($lang){
        if(strpos($this->default_value,':')==1) {
            $value = unserialize($this->default_value);
            if($lang=='ar')
                return $value['ar'];
            else
                return $value['en'];
        } else
            return $this->default_value;
    }
    */

    /*
     * End of attributes
     */

    public function attributeValue(){
        return $this->hasMany('App\Models\AttributeValue','attribute_id');
    }

    public function category(){
        return $this->belongsTo('App\Models\AttributeCategory','attribute_category_id');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'attributes.attribute_category_id',
            'attributes.type',
            'attributes.default_value',
            'attributes.default_value',
            'attributes.name_ar',
            'attributes.name_en',
            'attributes.description_ar',
            'attributes.description_en',
            'attributes.multi_lang'
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
           //->join('')
            ;
    }
}
