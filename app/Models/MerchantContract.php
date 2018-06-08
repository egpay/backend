<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchantContract extends Model
{

    protected $table = 'merchant_contracts';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at','start_date','end_date'];
    protected $fillable = array('merchant_id', 'plan_id', 'price', 'start_date', 'end_date', 'staff_id', 'admin_name', 'admin_job_title','description');

    public function merchant()
    {
        return $this->belongsTo('App\Models\Merchant');
    }

    public function staff()
    {
        return $this->belongsTo('App\Models\Staff');
    }

    public function plan()
    {
        return $this->belongsTo('App\Models\MerchantPlan', 'plan_id');
    }

    public function upload()
    {
        return $this->morphMany('App\Models\Upload', 'upload','model_type','model_id');
    }

    public function contact()
    {
        return $this->morphMany('App\Models\Contacts', 'contact','model_type','model_id');
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_contracts.id',
            'merchant_contracts.price',
            'merchant_contracts.start_date',
            'merchant_contracts.end_date',
            'merchant_contracts.description',
            'merchants.id as merchant_id',
            'merchants.logo',
            'merchants.name_'.$langCode.' as name',
            'merchant_categories.name_'.$langCode.' as category_name',
            'merchants.status',
            'merchants.staff_id',
            'staff.firstname as staff_firstname',
            'staff.lastname as staff_lastname',
            'merchant_plans.title as plan_title',
            'merchant_plans.id as plan_id',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchants','merchants.id','=','merchant_contracts.merchant_id')
            ->join('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id')
            ->join('staff','staff.id','=','merchant_contracts.staff_id')
            ->join('merchant_plans','merchant_plans.id','=','merchant_contracts.plan_id');
    }


}