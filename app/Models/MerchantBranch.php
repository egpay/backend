<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class MerchantBranch extends Model 
{

    protected $table = 'merchant_branches';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'merchant_id',
        'name_ar',
        'name_en',
        'address_ar',
        'address_en',
        'description_ar',
        'description_en',
        'status',
        'latitude',
        'longitude',
        'area_id',
        'staff_id',
        'merchant_staff_id'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'merchant_id',
        'name_ar',
        'name_en',
        'status',
        'staff_id',
        'merchant_staff_id',
    ];

    public function scopeActive($query)
    {
        return $query->where('status','=','active');
    }

    public function scopeInActive($query)
    {
        return $query->where('status','=','in-active');
    }

    public function BranchImages()
    {
        return $this->hasMany('MerchantBranchesImage', 'merchant_branch_id');
    }

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function uploadmodel()
    {
        return $this->morphMany('App\Models\Upload', 'uploadmodel');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'area_id');
    }

    public function categories(){
        return $this->merchant->merchant_product_categories()
            ->with(['product'=>function($query){
                $query->where('status','=','active');
            }])
            ->with('product.uploadmodel');
    }

    public function orders(){
        return $this->hasMany('App\Models\Order', 'merchant_branch_id');
    }



    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_branches.id',
            "merchant_branches.name_$langCode as name",
            "merchant_branches.address_$langCode as address",
            "merchant_branches.latitude",
            "merchant_branches.longitude",
            "merchant_branches.area_id",
            "merchants.name_$langCode as merchant_name",
            'merchant_branches.status',
            'merchants.logo'
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchants','merchants.id','=','merchant_branches.merchant_id')
            ->leftJoin('merchant_contracts','merchant_contracts.id','=','merchants.merchant_contract_id')
            ->leftJoin('merchant_plans','merchant_plans.id','=','merchant_contracts.plan_id');
    }


    public static function findBranch($langCode,$lat,$lng,$dist=1000){
        return self::select([
            'merchant_branches.id',
            "merchant_branches.name_$langCode as name",
            "merchant_branches.address_$langCode as address",
            "merchant_branches.latitude",
            "merchant_branches.longitude",
            "merchant_branches.area_id",
            "merchants.name_$langCode as merchant_name",
            'merchant_branches.status',
            'merchants.logo',
            \DB::raw("( 6371 * acos( cos( radians($lat) ) * cos( radians( merchant_branches.latitude ) ) * cos( radians( merchant_branches.longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( merchant_branches.latitude ) ) ) ) as `distance`")
        ])
            ->whereRaw("( 6371 * acos( cos( radians($lat) ) * cos( radians( merchant_branches.latitude ) ) * cos( radians( merchant_branches.longitude ) - radians($lng) ) + sin( radians($lat) ) * sin( radians( merchant_branches.latitude ) ) ) ) < ?",[$dist])
            ->join('merchants','merchants.id','=','merchant_branches.merchant_id')
            ->distinct()
            ;
    }

}