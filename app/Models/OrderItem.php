<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model 
{

    protected $table = 'order_items';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('order_id', 'merchant_product_id','price','qty');

    public function order()
    {
        return $this->belongsTo('App\Models\Order','order_id');
    }

    public function merchant_product()
    {
        return $this->belongsTo('App\Models\MerchantProduct');
    }

    public function orderItemAttribute(){
        return $this->hasMany('App\Models\OrderItemAttribute','order_item_id','id');
    }



    /*
 * Custom Function
 */

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'order_items.id',
            'order_items.order_id',
            'order_items.merchant_product_id',
            'order_items.qty',
            'orders.total',
            'orders.is_paid',
            'orders.created_at',
            'merchant_branches.name_'.$langCode.' as branch_name',
            'merchant_products.price',
            'merchant_products.name_'.$langCode.' as product_name',
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('orders','orders.id','=','order_items.order_id')
            ->leftJoin('merchant_products','order_items.merchant_product_id','=','merchant_products.id')
            ->leftJoin('merchant_branches','orders.merchant_branch_id','=','merchant_branches.id')
            ;
    }


}