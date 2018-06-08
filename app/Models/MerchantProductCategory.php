<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantProductCategory extends Model 
{

    protected $table = 'merchant_product_categories';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = [
        'approved_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'merchant_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'icon',
        'status',
        'created_by_merchant_staff_id',
        'approved_by_staff_id',
        'approved_at'
    ];

    public function scopeActive($query)
    {
        return $query->where('status','=','active');
    }

    public function scopeInActive($query)
    {
        return $query->where('status','=','in-active');
    }

    public function products(){
        return $this->hasMany('App\Models\MerchantProducts','merchant_product_category_id');
    }

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant');
    }

    public function merchant_staff(){
        return $this->belongsTo('App\Models\MerchantStaff','created_by_merchant_staff_id');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function product(){
        return $this->hasMany('App\Models\MerchantProduct','merchant_product_category_id');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_product_categories.id',
            'merchant_product_categories.merchant_id',
            'merchant_product_categories.name_'.$langCode.' as name',
            'merchant_product_categories.description_'.$langCode.' as description',
            'merchant_product_categories.icon',
            'merchant_product_categories.approved_at',
            'merchant_product_categories.created_at',

            // Merchant Staff
            'merchant_product_categories.created_by_merchant_staff_id',
            'merchant_product_categories.approved_by_staff_id',

            // Merchants
            "merchants.name_$langCode as merchant_name",
            "merchants.logo as merchant_logo",

            // Merchant Categories
            "merchant_categories.name_$langCode as merchant_category_name"

        ];


        foreach (listLangCodes() as $key => $value){
            $columns[] = "merchant_product_categories.name_".$key;
            $columns[] = "merchant_product_categories.description_".$key;
        }

        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchants','merchants.id','=','merchant_product_categories.merchant_id')
            ->join('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id');
    }

}