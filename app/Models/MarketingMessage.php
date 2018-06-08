<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MarketingMessage extends Model
{
    protected $table = 'marketing_messages';
    public $timestamps = true;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'message_type',
        'user_type',
        'title',
        'name_ar',
        'name_en',
        'content_ar',
        'content_en',
        'url_ar',
        'url_en',
        'image',
        'filter_type',
        'filter_data',
        'send_at',
        'status'
    ];

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
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
            \DB::raw("(SELECT COUNT(*) FROM `merchant_staff` WHERE merchant_staff.merchant_id = merchants.id) as `count_staff`"),
            \DB::raw("(SELECT COUNT(*) FROM `merchant_staff_group` WHERE merchant_staff_group.merchant_id = merchants.id) as `count_staff_group`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
                ->join('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id')
                ->leftJoin('merchant_contracts','merchant_contracts.id','=','merchants.merchant_contract_id')
                ->leftJoin('merchant_plans','merchant_plans.id','=','merchant_contracts.plan_id')
                ->leftJoin('staff','staff.id','=','merchants.staff_id')
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

}