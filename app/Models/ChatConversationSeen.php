<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatConversationSeen extends Model
{

    protected $table = 'chat_conversation_seen';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'model_id',
        'model_type',
        'chat_conversation_id',
        'last_chat_message_id'
    ];

    public function model(){
        return $this->morphTo();
    }




}