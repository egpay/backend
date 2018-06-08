<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatSocketAccess extends Model
{

    protected $table = 'chat_socket_access';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at'];
    protected $fillable = [
        'id',
        'model_id',
        'model_type',
        'chat_conversation_id',
        'socket_id'
    ];

    public function model(){
        return $this->morphTo();
    }

}