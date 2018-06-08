<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantCategory extends Model 
{

    protected $table = 'merchant_categories';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'icon',
        'attribute_categories',
        'status',
        'main_category_id',
        'staff_id'
    ];


    public function getAttributeCategoriesAttribute()
    {
        return unserialize($this->attributes['attribute_categories']);
    }


    public function setAttributeCategoriesAttribute($value)
    {
        $this->attributes['attribute_categories'] = serialize($value);
    }


    public function main_category(){
        return $this->belongsTo('App\Models\MerchantCategory','main_category_id');
    }

    public function Merchants()
    {
        return $this->hasMany('App\Models\Merchant', 'id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_categories.id',
            'merchant_categories.name_'.$langCode.' as name',
            'merchant_categories.description_'.$langCode.' as description',
            'merchant_categories.icon',
            'merchant_categories.status',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns);

    }


}