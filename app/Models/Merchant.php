<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Merchant extends Model
{
    public $timestamps = true;
    use SoftDeletes,LogsActivity;

    public $modelPath = 'App\Models\Merchant';

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'is_reseller',
        'area_id',
        'name_ar',
        'name_en',
        'description_ar',
        'description_en',
        'address',
        'logo',
        'merchant_contract_id',
        'merchant_category_id',
        'attribute_categories',
        'status',
        'staff_id',
        'parent_id'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'is_reseller',
        'logo',
        'merchant_contract_id',
        'merchant_category_id',
        'status',
        'parent_id',
    ];

    public function scopeActive($query)
    {
        return $query->where('status','=','active');
    }

    public function scopeInActive($query)
    {
        return $query->where('status','=','in-active');
    }

    public function scopeReseller($query)
    {
        return $query->where('is_reseller','=','active');
    }


    public function merchant_category()
    {
        return $this->belongsTo('App\Models\MerchantCategory');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }



    public function merchant_contract()
    {
        return $this->hasOne('App\Models\MerchantContract');
    }



    public function contactmodel()
    {
        return $this->morphMany('App\Models\Contacts', 'model');
    }

    public function uploadmodel()
    {
        return $this->morphMany('App\Models\Upload', 'uploadmodel');
    }

    public function MerchantImages()
    {
        return $this->morphMany('App\Models\Upload', 'model');
    }




    public function merchant_product_catgories(){
        return $this->merchant_product_categories();
    }

    public function merchant_product_categories()
    {
        return $this->hasMany('App\Models\MerchantProductCategory', 'merchant_id');
    }



    public function merchant_products()
    {
        return $this->hasMany('App\Models\MerchantProduct', 'merchant_id');
    }



    public function merchant_staff_group()
    {
        return $this->hasMany('App\Models\MerchantStaffGroup', 'merchant_id');
    }


    public function merchant_branch()
    {
        return $this->hasMany('App\Models\MerchantBranch', 'merchant_id');
    }

    // ---------- WALLET
    public function wallet(){
        return $this->morphMany('App\Models\Wallet','walletowner');
    }

    public function paymentWallet(){
        return $this->morphOne('App\Models\Wallet','walletowner');
        //->where('type','payment');
    }

    public function eCommerceWallet(){
        return $this->morphOne('App\Models\Wallet','walletowner')
            ->where('type','e-commerce');
    }

    public function payment_invoice(){
        return $this->morphMany('App\Models\PaymentInvoice','creatable');
    }

    public function appointment(){
        return $this->morphMany('App\Models\Appointment','model');
    }

    // ---------- WALLET


    public function order()
    {
        return $this->hasManyThrough('App\Models\Order', 'App\Models\MerchantBranch','merchant_id','merchant_branch_id');
    }


    public function attributeCategories($langCode){
        return AttributeCategory::select([
            'attribute_categories.id',
            'attribute_categories.name_'.$langCode.' as name',
            'attribute_categories.description_'.$langCode.' as description',
        ])
            ->whereIn('id',$this->merchant_category->attribute_categories);
    }


    public function category()
    {
        return $this->belongsTo('App\Models\MerchantCategory', 'merchant_category_id');
    }

    public function Merchant_Attachment()
    {
        return $this->hasMany('MerchantAttachement', 'merchant_id');
    }

    public function MerchantContracts()
    {
        return $this->hasMany('App\Models\MerchantContract', 'merchant_id');
    }

    public function contract(){
        return $this->belongsTo('App\Models\MerchantContract','merchant_contract_id');
    }

    public function contact()
    {
        return $this->morphMany('App\Models\Contacts', 'model');
    }


    public function MerchantBranches()
    {
        return $this->hasMany('MerchantBranch', 'merchant_id');
    }

    public function MerchantStaff()
    {
        return $this->hasManyThrough('App\Models\MerchantStaff', 'App\Models\MerchantStaffGroup','merchant_id','merchant_staff_group_id','id');
    }

    public function MerchantStaffGroup()
    {
        return $this->hasMany('App\Models\MerchantStaffGroup', 'merchant_id');
    }


    public function productCategories()
    {
        return $this->hasMany('App\Models\MerchantProductCategory', 'merchant_id');
    }

    public function products()
    {
        return $this->hasMany('App\Models\MerchantProduct', 'merchant_id');
    }

    public function MerchantWallet()
    {
        return $this->hasOne('Wallet', 'user_id');
    }

    public function MerchantLoyaletyWallet()
    {
        return $this->hasOne('LoyaltyWallet', 'user_id');
    }

    public function AreaID()
    {
        return $this->hasOne('Area', 'id');
    }


    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\Merchant');
    }

    public function child()
    {
        return $this->hasMany('App\Models\Merchant', 'parent_id');
    }
    /*
     * Custom Function
     */

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchants.id',
            'merchants.logo',
            'merchants.name_'.$langCode.' as name',
            'merchant_categories.name_'.$langCode.' as category_name',
            'merchants.status',
            'merchants.staff_id',
            'staff.firstname as staff_firstname',
            'staff.lastname as staff_lastname',
            \DB::raw("(SELECT COUNT(*) FROM `merchant_branches` WHERE merchant_branches.merchant_id = merchants.id) as `count_branchs`"),
            \DB::raw("(SELECT COUNT(*) FROM `merchant_staff_groups` WHERE merchant_staff_groups.merchant_id = merchants.id) as `count_staff_group`"),
            \DB::raw("(SELECT COUNT(*) FROM `merchant_staff` WHERE merchant_staff.merchant_staff_group_id = merchant_staff_groups.id) as `count_staff`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id')
            ->leftJoin('merchant_contracts','merchant_contracts.id','=','merchants.merchant_contract_id')
            ->leftJoin('merchant_plans','merchant_plans.id','=','merchant_contracts.plan_id')
            ->leftJoin('merchant_staff_groups','merchant_staff_groups.merchant_id','=','merchants.id')
            ->leftJoin('staff','staff.id','=','merchants.staff_id')
            ->groupBy('merchants.id')
            ;
    }


    public static function getWithRelations($id){
        $columns   = array();
        $columns[] = 'merchants.*';

        // -- Contracts
        $columns[] = 'merchant_contracts.plan_id';
        $columns[] = 'merchant_contracts.price';
        $columns[] = 'merchant_contracts.start_date';
        $columns[] = 'merchant_contracts.end_date';
        // -- Contracts

        // -- Plan
        $columns[] = 'merchant_plans.title';
        $columns[] = 'merchant_plans.months';
        $columns[] = 'merchant_plans.amount';
        // -- Plan

        // -- Category
        foreach (listLangCodes() as $key => $value){
            $columns[] = "merchant_categories.name_$key as `merchant_categories_name_$key`";
        }
        // -- Category

        return self::select($columns)
            ->leftJoin('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id')
            ->leftJoin('merchant_contracts','merchant_contracts.id','=','merchants.merchant_contract_id')
            ->leftJoin('merchant_plans','merchant_plans.id','=','merchant_contracts.plan_id')
            ->where('merchants.id',$id)
            ->first();

    }

    /*
  * Merchant Dashboard
  */
    public function DashboardBranchOrders($langCode,array $additionColumn = []){
        $columns = [
            'merchant_branches.name_'.$langCode.' as name',
            \DB::raw('CONCAT(orders.id) AS orders_count'),
            \DB::raw('CONCAT(YEAR(orders.created_at),\'-\',MONTH(orders.created_at)) AS month'),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::order()
            ->select($columns)
            ->groupBy(['month','name'])
            ;
    }


}