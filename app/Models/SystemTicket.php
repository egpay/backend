<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SystemTicket extends Model
{

    protected $table = 'email';
    public $timestamps = true;

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'sendermodel_type',
        'sendermodel_id',
        'sender_star',
        'receivermodel_type',
        'receivermodel_id',
        'receiver_star',
        'subject',
        'body',
        'file',
        'seen',
        'seen_id',
        'parent_id'
    ];

    public function sendermodel(){
        return $this->morphTo();
    }

    public function receivermodel(){
        return $this->morphTo();
    }

    public function receiver(){
        return $this->hasMany('App\Models\EmailReceiver','email_id','id')->with('receivermodel');
    }

    public function parent(){
        return $this->belongsTo('App\Models\SystemTicket');
    }


    public function starForStaff($staffID){
        return $this->hasOne('App\Models\EmailStar','email_id')
            ->where('model_type','App\\Models\\Staff')
            ->where('model_id',$staffID);

    }

}