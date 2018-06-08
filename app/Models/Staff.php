<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\SystemTicket;
use Spatie\Activitylog\Traits\LogsActivity;

class Staff extends Authenticatable
{

    protected $table = 'staff';
    public $timestamps = true;

    use SoftDeletes;
    use Notifiable,LogsActivity;

    public $modelPath = 'App\Models\Staff';
    protected $dates = ['lastlogin','created_at','updated_at','deleted_at'];
    protected $fillable = [
        'firstname',
        'lastname',
        'national_id',
        'email',
        'mobile',
        'avatar',
        'gender',
        'birthdate',
        'address',
        'password',
        'remember_token',
        'description',
        'job_title',
        'status',
        'language_id',
        'permission_group_id',
        'supervisor_id',
        'menu_type',
        'lastlogin',
        'language_key',
    ];
    protected $hidden = array('password', 'remember_token');

    /*
     * Log Activity
     */
    protected static $logAttributes = [
        'national_id',
        'email',
        'mobile',
        'password',
        'job_title',
        'status',
        'permission_group_id',
        'supervisor_id',
    ];


    public static function StaffPerms($staffID){
        return Staff::find($staffID)->permission->pluck('route_name');
    }

    public function getFullnameAttribute($key)
    {
        if(isset($this->firstname) && strlen($this->firstname))
            $name = $this->firstname;
        if(isset($this->middlename) && strlen($this->middlename))
            $name .= ' ' .$this->middlename;

        if(isset($this->lastname) && strlen($this->lastname))
            $name .= ' ' .$this->lastname;

        return $name;
    }

    public function is_supervisor(){
        return $this->permission_group->is_supervisor == 'yes';
    }

    public function managed_staff(){
        return $this->hasMany('App\Models\Staff','supervisor_id');
    }


    public function managed_staff_ids(){
        if($this->permission_group->is_supervisor == 'no')
            return [$this->id];

        $data = $this->hasMany('App\Models\Staff','supervisor_id')->get(['id']);
        if(!$data)
            return [$this->id];

        $data = $data->toArray();

        return array_merge([$this->id],array_column($data,'id'));
    }

    public function permission_group(){
        return $this->belongsTo('App\Models\PermissionGroup','permission_group_id');
    }

    public function permission(){
        return $this->hasManyThrough('App\Models\Permission','App\Models\PermissionGroup','id','permission_group_id','permission_group_id');
    }

    public function merchant_category()
    {
        return $this->hasMany('App\Models\MerchantCategory', 'staff_id');
    }

    public function merchant()
    {
        return $this->hasMany('App\Models\Merchant', 'staff_id');
    }

    public function merchant_contract()
    {
        return $this->hasMany('App\Models\Staff', 'staff_id');
    }

    public function contactmodel()
    {
        return $this->morphMany('App\Models\Contact', 'contactmodel');
    }


    public static function email_sent($id,$search = null){
        $result = SystemTicket::where('sendermodel_type','App\\Models\\Staff')
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
        $result = SystemTicket::where('receivermodel_type','App\\Models\\Staff')
            ->where(function($query) use($id) {
                $query->where('receivermodel_id',$id);
                $query->orWhereNull('receivermodel_id');
            });


        if($search){
            $result->where(function($query) use($search){
                $query->where('subject','LIKE',"%$search%")
                    ->orWhere('body','LIKE',"%$search%");
            });
        }

        return $result;
    }

    public function email_star(){
        return $this->morphMany('App\Models\EmailStar','model');
    }



    // --------------- CHAT

    public function chat_conversation_seen($ID){
        $result = $this->morphOne('App\Models\ChatConversationSeen','model')
            ->where('chat_conversation_id',$ID)
            ->whereRaw("(SELECT `id` FROM `chat_messages` WHERE `chat_messages`.`chat_conversation_id` = `chat_conversation_seen`.`chat_conversation_id` ORDER BY `id` DESC LIMIT 1) = `last_chat_message_id`")
            ->first();
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function chat_socket_access(){
        return $this->morphMany('App\Models\ChatSocketAccess','model');
    }

    public function chat_conversation_from(){
        return $this->morphMany('App\Models\ChatConversation','from');
    }

    public function chat_conversation_to(){
        return $this->morphMany('App\Models\ChatConversation','to');
    }

    public function conversation_seen(){
        return $this->morphMany('App\Models\ChatConversationSeen','model');
    }

    // --------------- CHAT


    public function PaymentTransactions(){
        return $this->morphMany('App\Models\PaymentTransactions','model');
    }



    // ---------- WALLET
    public function wallet(){
        return $this->morphMany('App\Models\Wallet','walletowner');
    }

    public function paymentWallet(){
        return $this->morphOne('App\Models\Wallet','walletowner')
            ->where('type','payment');
    }

    public function eCommerceWallet(){
        return $this->morphOne('App\Models\Wallet','walletowner')
            ->where('type','e-commerce');
    }

    public function payment_invoice(){
        return $this->morphMany('App\Models\PaymentInvoice','creatable');
    }

    // ---------- WALLET



    // علشان في اكتر من حد بيعمل لوجن فده لو هو مرشنت ستاف هيديلو اي دي المرشنت غير كدة هيديلة اي دي اليوزر
    public function authParentID(){
        return $this->id;
    }



    public function activity_log(){
        return $this->morphMany('Spatie\Activitylog\Models\Activity','causer');
    }


    public function appointment_status(){
        return $this->morphMany('App\Models\AppointmentStatus','model');
    }


}