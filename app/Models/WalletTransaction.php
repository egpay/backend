<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class WalletTransaction extends Model 
{

    protected $table = 'transactions';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;
    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'model_id',
        'model_type',
        'amount',
        'from_id',
        'to_id',
        'type',
        'status',
        'latitude',
        'longitude',
        'creatable_id',
        'creatable_type'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'model_id',
        'model_type',
        'amount',
        'from_id',
        'to_id',
        'type',
        'status',
        'latitude',
        'longitude',
        'creatable_id',
        'creatable_type',
    ];


    public function model(){
        return $this->morphTo();
    }


    public function creatable(){
        return $this->morphTo();
    }

    public function model_data(){
        return $this->morphTo('model');
    }


    public function fromWallet(){
        return $this->belongsTo('App\Models\Wallet','from_id');
    }

    public function toWallet(){
        return $this->belongsTo('App\Models\Wallet','to_id');
    }

    public function transactions_status(){
        return $this->hasMany('App\Models\TransactionsStatus','transaction_id');
    }


    public static function viewData($langCode,array $additionColumn = [],$walletid){
        $columns = [
            'transactions.id',
            'transactions.from_id',
            'transactions.to_id',
            'transactions.model_id',
            'transactions.model_type',
            'transactions.amount',
            'transactions.type',
            'transactions.status',
            'transactions.created_at',
            \DB::raw("(CASE WHEN (transactions.from_id = '$walletid') THEN 'send' WHEN (transactions.to_id='$walletid') THEN 'receive' END) AS `pay_type`"),
            //'merchant_branches.name_'.$langCode.' as branch_name',
            //\DB::raw("(SELECT COUNT(*) FROM `order_items` WHERE order_items.order_id = orders.id) as `count_order_items`"),
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->where(function($query)use($walletid){
                $query->where('from_id','=',$walletid);
                $query->orwhere('to_id','=',$walletid);
            })
            ->with(['order'=>function($query)use($langCode){
                $query->with(['merchant_branch'=>function($query)use($langCode){
                    $query->select(['merchant_branches.id','merchant_branches.merchant_id','merchant_branches.name_ar','merchant_branches.name_en']);
                    $query->with(['merchant'=>function($query)use($langCode){
                        $query->select(['id','name_'.$langCode.' as name','description_'.$langCode.' as description','logo']);
                    }]);
                }]);
            }])
            //->join('merchant_branches','merchant_branches.id','=','orders.merchant_branch_id')
            ;

    }




}