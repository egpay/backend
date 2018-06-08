<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\User;

class Coupon extends Model
{

    protected $table = 'coupons';
    public $timestamps = true;

    use LogsActivity;

    protected $dates = ['created_at','updated_at','start_date','end_date'];
    protected $fillable = [
        'merchant_id',
        'type',
        'code',
        'description_ar',
        'description_en',
        'reward',
        'quantity',
        'users',
        'items',
        'start_date',
        'end_date',
        'staff_id',
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'merchant_id',
        'type',
        'code',
        'reward',
        'quantity',
        'users',
        'items',
        'start_date',
        'end_date',
        'staff_id',
    ];

    public function getUsersAttribute($value){
        return @explode(',',$value);
    }

    public function getItemsAttribute($value){
        return @explode(',',$value);
    }


    public function setUsersAttribute($value){
        $this->attributes['users']   = @implode(',',$value);
    }

    public function setItemsAttribute($value){
        $this->attributes['items']   = @implode(',',$value);
    }


    public function getUsers(){
        return User::whereIn('id',$this->users);
    }

    public function getItems(){
        if($this->type=='product')
            return MerchantProduct::whereIn('id',$this->items);
        else
            return PaymentServices::whereIn('id',$this->items);
    }

    public function merchant(){
        return $this->belongsTo('App\Models\Merchant');
    }

    public function staff(){
        return $this->belongsTo('App\Models\Staff');
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'coupons.id',
            'coupons.merchant_id',
            'coupons.type',
            "coupons.description_$langCode as name",
            "coupons.reward",
            "coupons.reward_type",
            "coupons.quantity",
            "coupons.users",
            "coupons.items",
            "coupons.start_date",
            'coupons.end_date',
            'coupons.created_at',
            'merchants.name_'.$langCode.' as merchant_name',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchants','merchants.id','=','coupons.merchant_id')
            ;
    }
}