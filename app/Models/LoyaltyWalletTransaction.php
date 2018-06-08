<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class LoyaltyWalletTransaction extends Model
{

    protected $table = 'loyalty_transactions';
    public $timestamps = true;

    use SoftDeletes,LogsActivity;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'transaction_id',
        'point',
        'from_id',
        'to_id',
        'status'
    ];

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'transaction_id',
        'point',
        'from_id',
        'to_id',
        'status',
    ];

    public function model(){
        return $this->morphTo();
    }

    public function fromWallet(){
        return $this->belongsTo('App\Models\LoyaltyWallet','from_id');
    }

    public function toWallet(){
        return $this->belongsTo('App\Models\LoyaltyWallet','to_id');
    }

    public static function viewData($langCode,array $additionColumn = [],$walletid){
        $columns = [
            'loyalty_wallet_transactions.id',
            'loyalty_wallet_transactions.from_id',
            'loyalty_wallet_transactions.to_id',
            'loyalty_wallet_transactions.model_id',
            'loyalty_wallet_transactions.model_type',
            'loyalty_wallet_transactions.amount',
            'loyalty_wallet_transactions.type',
            'loyalty_wallet_transactions.status',
            'loyalty_wallet_transactions.created_at',
            \DB::raw("(CASE WHEN (loyalty_wallet_transactions.from_id = '$walletid') THEN 'send' WHEN (loyalty_wallet_transactions.to_id='$walletid') THEN 'receive' END) AS `pay_type`"),
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