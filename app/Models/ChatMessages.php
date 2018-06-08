<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{

    protected $table = 'chat_messages';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'chat_conversation_id',
        'model_id',
        'model_type',
        'message',
        'file'
    ];


    public function model(){
        return $this->morphTo();
    }


}