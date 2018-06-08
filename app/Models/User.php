<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use Notifiable,SoftDeletes,HasRoles,LogsActivity,HasApiTokens;

    public $modelPath = 'App\Models\User';
    protected $table = 'users';
    public    $timestamps = true;

    protected $dates = [
        'lastlogin',
        'verified_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'user_name',
        'firstname',
        'middlename',
        'lastname',
        'email',
        'mobile',
        'password',
        'remember_token',
        'image',
        'gender',
        'national_id',
        'birthdate',
        'address',
        'status',
        'parent_id',
        'nationality_id',
        'national_id_image',
        'facebook_user_id',
        'google_user_id',
        'verified_at',
        'lastlogin',
        'language_key',
    ];

    protected $hidden = array('password', 'remember_token');

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected static $logAttributes = [
        'firstname',
        'lastname',
        'email',
        'mobile',
        'password',
        'image',
        'national_id',
        'birthdate',
        'address',
        'status',
        'parent_id',
        'lastlogin'
    ];



    public function parent(){
        return $this->belongsTo('App\Models\User','parent_id');
    }


    public function PaymentTransactions(){
        return $this->morphMany('App\Models\PaymentTransactions','model');
    }


    public function creatable()
    {
        return $this->morphMany('App\Models\Order', 'creatable');
    }

    public function verification(){
        return $this->hasOne('App\Models\Verification','user_id');
    }

    public function verify($code){
        if($this->verification->code != $code)
            return false;

        $user = $this;
        DB::transaction(function () use($user) {
            $user->update([
                'verified_at'   => Carbon::now(),
            ]);
            $user->verification->delete();

            $user->wallet()->create(['walletowner_id'=>$user->id,'type'=>'e-commerce']);
            $user->wallet()->create(['walletowner_id'=>$user->id,'type'=>'loyalty']);
        }, 2);
        return true;
    }


    public function getFullNameAttribute()
    {
        $name = $this->firstname;
        if(isset($this->middlename) && strlen($this->middlename)) {
            $name .= ' ' .$this->middlename;
        }
        $name .= ' '.$this->lastname;
        return $name;
    }


    // علشان في اكتر من حد بيعمل لوجن فده لو هو مرشنت ستاف هيديلو اي دي المرشنت غير كدة هيديلة اي دي اليوزر
    public function authParentID(){
        return $this->id;
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

    public function loyaltyWallet()
    {
        return $this->morphOne('App\Models\Wallet','walletowner')
            ->where('type','loyalty');
    }

    public function payment_invoice(){
        return $this->morphMany('App\Models\PaymentInvoice','creatable');
    }

    // ---------- WALLET


    public function PwdReset(){
        return $this->hasOne('App\Models\PwdReset','email','email');
    }

    public function scopeActive($query)
    {
        return $query->where('status','=','active');
    }

    public function scopeInActive($query)
    {
        return $query->where('status','=','in-active');
    }

}
