<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = array('merchant_branch_id', 'creatable_id', 'creatable_type', 'comment', 'commission', 'commission_type', 'coupon','total', 'is_paid','qr_code');

    public function creatable()
    {
        return $this->morphTo();
    }

    public function statusable()
    {
        return $this->morphMany('App\Models\OrderStatus', 'statusable');
    }

    public function orderitems(){
        return $this->hasMany('App\Models\OrderItem','order_id')->with(['merchant_product','orderItemAttribute']);
    }

    public function trans(){
        return $this->morphMany('App\Models\WalletTransaction','model')
            ->with('fromWallet')->with('toWallet')
            ;
    }


    public function merchant(){
        return $this->merchant_branch->merchant();
    }

    public function merchant_branch(){
        return $this->belongsTo('App\Models\MerchantBranch','merchant_branch_id');
    }

    public function items(){
        return $this->orderitems()
            ->join('merchant_products','merchant_products.id','order_items.merchant_product_id')
            ->select(['order_items.id','order_items.merchant_product_id','order_items.price','order_items.qty','merchant_products.name_ar','merchant_products.name_en']);
    }

    public function transactions(){
        return $this->trans()
            ->join('wallet as fwallet','fwallet.id','=','transactions.from_id')
            ->join('wallet as twallet','twallet.id','=','transactions.to_id')
            ->select([
                'transactions.id','transactions.type','transactions.amount','transactions.status',
                'fwallet.id as from_id',
                \DB::raw("IF(fwallet.walletowner_type,'App\\Models\\User', (SELECT mobile FROM users WHERE users.id=fwallet.walletowner_id)) as `from_name`"),
                'twallet.id as to_id',
                \DB::raw("IF(twallet.walletowner_type,'App\\Models\\Merchant', (SELECT CONCAT(`name_en`,' - ',`name_ar`) FROM merchants WHERE merchants.id=twallet.walletowner_id) ) as `to_name`")
            ]);
    }

    public static function viewBranchOrders($langCode,array $additionColumn = []){
        $columns = [
            'orders.id',
            'orders.total',
            'orders.is_paid',
            'orders.created_at',
            'merchant_branches.name_'.$langCode.' as branch_name',
            \DB::raw("(SELECT COUNT(*) FROM `order_items` WHERE order_items.order_id = orders.id) as `count_order_items`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchant_branches','merchant_branches.id','=','orders.merchant_branch_id')
            ;
    }

    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'orders.id',
            'orders.total',
            'orders.is_paid',
            'orders.created_at',
            'merchant_branches.name_'.$langCode.' as branch_name',
            \DB::raw("(SELECT COUNT(*) FROM `order_items` WHERE order_items.order_id = orders.id) as `count_order_items`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchant_branches','merchant_branches.id','=','orders.merchant_branch_id')
            ;
    }


    public static function SystemViewData($langCode,array $additionColumn = []){
        $columns = [
            'orders.id',
            'orders.total',
            'orders.is_paid',
            'orders.created_at',
            'merchant_branches.merchant_id AS merchant_id',
            'merchant_branches.name_'.$langCode.' as branch_name',
            'merchant_branches.id as branch_id',
            'merchants.name_'.$langCode.' as merchant_name',
            \DB::raw("(SELECT COUNT(*) FROM `order_items` WHERE order_items.order_id = orders.id) as `count_order_items`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchant_branches','merchant_branches.id','=','orders.merchant_branch_id')
            ->join('merchants','merchant_branches.merchant_id','=','merchants.id')
            ;
    }

}