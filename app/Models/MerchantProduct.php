<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MerchantProduct extends Model
{

    protected $table = 'merchant_products';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['deleted_at'];
    protected $fillable = array('merchant_product_category_id', 'name_ar', 'name_en', 'description_ar','merchant_id', 'description_en', 'price', 'created_by_merchant_staff_id', 'approved_by_staff_id', 'approved_at');

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'merchant_product_category_id',
        'name_ar',
        'description_ar',
        'merchant_id',
        'price',
        'approved_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('status','=','active');
    }

    public function scopeInActive($query)
    {
        return $query->where('status','=','in-active');
    }

    public function merchant_staff(){
        return $this->belongsTo('App\Models\MerchantStaff','created_by_merchant_staff_id');
    }

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant','merchant_id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\MerchantProductCategory','merchant_product_category_id');
    }

    public function uploadmodel(){
        return $this->morphMany('App\Models\Upload', 'model');
    }

    public function upload()
    {
        return $this->morphMany('App\Models\Upload', 'upload','model_type','model_id');
    }

    public function product_price(){
        $price = $this->price;
        //TODO add discount for products here
        return $price;
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff','approved_by_staff_id');
    }

    public function attribute(){
        return $this->hasMany('App\Models\ProductAttribute','product_id');
    }


    public static function viewDataApi($langCode,array $additionColumn = []){
        $columns = [
            'merchant_products.id',
            'merchant_products.merchant_product_category_id',
            'merchant_products.name_ar',
            'merchant_products.name_en',
            'merchant_products.description_ar',
            'merchant_products.description_en',
            'merchant_products.price',
            'merchant_products.created_by_merchant_staff_id',
            'merchant_products.approved_by_staff_id',
            'merchant_products.approved_at',
            'merchant_products.created_at',
            'merchant_products.updated_at',
            'merchant_products.deleted_at',
            'merchant_products.status',
            'merchants.logo',
            'merchants.name_'.$langCode.' as merchant_name',
            'merchant_categories.name_'.$langCode.' as merchant_category_name',
            'merchants.id as merchant_id',
            'merchant_product_categories.merchant_id',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchant_product_categories','merchant_product_categories.id','=','merchant_products.merchant_product_category_id')
            ->join('merchants','merchants.id','=','merchant_product_categories.merchant_id')
            ->leftJoin('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_products.id',
            'merchant_products.merchant_product_category_id',
            'merchant_products.name_ar',
            'merchant_products.name_en',
            'merchant_products.description_ar',
            'merchant_products.description_en',
            'merchant_products.price',
            'merchant_products.created_by_merchant_staff_id',
            'merchant_products.approved_by_staff_id',
            'merchant_products.approved_at',
            'merchant_products.created_at',
            'merchant_products.updated_at',
            'merchant_products.deleted_at',
            'merchant_products.status',
            'merchants.logo',
            'merchants.name_'.$langCode.' as merchant_name',
            'merchant_categories.name_'.$langCode.' as merchant_category_name',
            'merchants.id as merchant_id',
            //'CONCAT(merchant_staff.firstname," ",merchant_staff.lastname) as created_by_merchant_staff_name',
            \DB::raw("CONCAT(staff.firstname,' ',staff.lastname) as approved_by_staff_name"),
            //'merchant_product_categories.merchant_id',


//            'merchant_categories.name_'.$langCode.' as category_name',
//            'merchants.status',
//            'merchants.staff_id',
//            'staff.firstname as staff_firstname',
//            'staff.lastname as staff_lastname',
//            \DB::raw("(SELECT COUNT(*) FROM `merchant_branches` WHERE merchant_branches.merchant_id = merchants.id) as `count_branchs`"),
//            \DB::raw("(SELECT COUNT(*) FROM `merchant_staff` WHERE merchant_staff.merchant_id = merchants.id) as `count_staff`"),
//            \DB::raw("(SELECT COUNT(*) FROM `merchant_staff_group` WHERE merchant_staff_group.merchant_id = merchants.id) as `count_staff_group`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)->distinct()
            ->join('merchant_product_categories','merchant_product_categories.id','=','merchant_products.merchant_product_category_id')
            ->join('merchants','merchants.id','=','merchant_product_categories.merchant_id')
            ->leftJoin('merchant_branches','merchant_branches.merchant_id','=','merchants.id')
            ->leftJoin('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id')
            ->leftJoin('merchant_staff','merchant_staff.id','=','merchant_products.created_by_merchant_staff_id')
            ->leftJoin('staff','staff.id','=','merchant_products.approved_by_staff_id')
            ;
    }
}