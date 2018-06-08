<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;


class MerchantStaff extends Authenticatable
{

    protected $table = 'merchant_staff';
    public $timestamps = true;

    use SoftDeletes;
    use Notifiable,HasApiTokens,LogsActivity;

    public $modelPath = 'App\Models\MerchantStaff';

    protected $dates = ['lastlogin','created_at','updated_at','deleted_at'];
    protected $fillable = [
        'merchant_staff_group_id',
        'branches',
        'firstname',
        'lastname',
        'national_id',
        'email',
        'password',
        'remember_token',
        'mobile',
        'address',
        'birthdate',
        'status',
        'must_change_password',
        'lastlogin',
        'language_key',
    ];

    protected $hidden = array('password');


    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'firstname',
        'lastname',
        'email',
        'mobile',
        'password',
        'national_id',
        'birthdate',
        'address',
        'status',
        'lastlogin'
    ];

    public function getNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public static function MerchantStaffPerms($staffID){
        return MerchantStaff::find($staffID)->merchant_staff_permission->pluck('route_name');
    }

    public function merchant_staff_group()
    {
        return $this->belongsTo('App\Models\MerchantStaffGroup', 'merchant_staff_group_id');
    }



    public function merchant_staff_permission(){
        return $this->hasManyThrough('App\Models\MerchantStaffPermission', 'App\Models\MerchantStaffGroup','id','merchant_staff_group_id','merchant_staff_group_id');
    }


    public function merchant_staff_orders(){
        $orders = Order::select(['orders.*'])
            ->join('merchant_branches','merchant_branches.id','=','orders.merchant_branch_id')
            ->wherein('merchant_branches.id',explode(',',$this->staff_branches()))
        ;
        return $orders;
    }

    public function staff_branches(){
        if($this->merchant()->merchant_staff_group()->first()->id == $this->merchant_staff_group_id)
            return $this->merchant()->merchant_branch()->pluck('id')->toArray();
        else {
            return array_filter($this->branches);
        }
    }


    /*
  * Email Methods Start
  */
    public static function email_sent($id,$search = null){
        $result = SystemTicket::where('sendermodel_type','App\\Models\\MerchantStaff')
            ->where(function($query) use($id) {
                $query->where('sendermodel_id',$id);
                $query->orWhereNull('sendermodel_id');
            });

        if($search){
            $result->where(function($query) use($search){
                $query->where('subject','LIKE',"%$search%")
                    ->orWhere('body','LIKE',"%$search%");
            });
        }

        return $result;
    }


    public static function email_receive($id,$search = null){
        $result = SystemTicket::select(['email.*','email_receiver.receivermodel_id','email_receiver.receivermodel_type','email_receiver.star','email_receiver.seen'])
            ->where('email_receiver.receivermodel_type','App\\Models\\MerchantStaff')
            ->where(function($query) use ($id){
                $query->where('email_receiver.receivermodel_id',$id);
                $query->orWhereNull('email_receiver.receivermodel_id');
            })
            ->join('email_receiver','email.id','=','email_receiver.email_id');


        if($search){
            $result->where(function($query) use($search){
                $query->where('email.subject','LIKE',"%$search%")
                    ->orWhere('email.body','LIKE',"%$search%");
            });
        }

        return $result;
    }


    public static function email_star($id){
        $result = SystemTicket::select(['email.*'])
            ->join('email_receiver','email.id','=','email_receiver.email_id')
            ->join('email_star','email.id','=','email_star.email_id')
            ->where(function($query) use ($id){
                $query->where('email_receiver.receivermodel_id',$id);
                $query->where('email_receiver.receivermodel_type','App\Models\MerchantStaff');
            });

        return $result;
    }








    public function merchant(){
        return $this->staff_group->merchant;
    }


    public function staff_group(){
        return $this->belongsTo('App\Models\MerchantStaffGroup','merchant_staff_group_id');
    }


    public function getBranchesAttribute($value){
        return explode(',',$value);
    }


    public static function viewData($langCode,array $additionColumn = []){
        $columns = [
            'merchant_staff.id',
            'merchant_staff.merchant_staff_group_id',
            'merchant_staff.firstname',
            'merchant_staff.lastname',
            'merchant_staff.national_id',
            'merchant_staff.email',
            'merchant_staff.mobile',
            'merchant_staff.address',
            'merchant_staff.birthdate',
            'merchant_staff.status',
            'merchant_staff.branches',
            'merchant_staff.lastlogin',
            'merchant_staff.merchant_staff_group_id',
            'merchant_staff_groups.merchant_id',
            'merchant_staff_groups.title as merchant_staff_group_title',
            'merchants.name_'.$langCode.' as merchant_name'
        ];
        $columns = array_merge($columns,$additionColumn);
        return self::select($columns)
            ->join('merchant_staff_groups','merchant_staff_groups.id','=','merchant_staff.merchant_staff_group_id')
            ->join('merchants','merchants.id','=','merchant_staff_groups.merchant_id')
            ->join('merchant_categories','merchant_categories.id','=','merchants.merchant_category_id');
    }


    /*
     * Payment
     */

    // ---------- WALLET
    public function wallet(){
        return $this->merchant()->wallet();
    }

    public function paymentWallet(){
        return $this->merchant()->paymentWallet();
    }

    public function eCommerceWallet(){
        return $this->merchant()->eCommerceWallet();
    }

    public function payment_invoice(){
        return $this->merchant()->payment_invoice();
    }

    // ---------- WALLET


    public function PaymentTransactions(){
        return $this->morphMany('App\Models\PaymentTransactions','model');
    }

    // علشان في اكتر من حد بيعمل لوجن فده لو هو مرشنت ستاف هيديلو اي دي المرشنت غير كدة هيديلة اي دي اليوزر
    public function authParentID(){
        return $this->staff_group->merchant->id;
    }

    public function mobileDevices(){
        return $this->morphMany('App\Models\MobileDevice','user');
    }

    public function findForPassport($username){
        return $this->where('id', $username)->first();
    }

}