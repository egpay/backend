<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ChatConversation extends Model
{

    protected $table = 'chat_conversation';
    public $timestamps = true;

    protected $dates = ['created_at','updated_at','deleted_at'];
    protected $fillable = [
        'from_id',
        'from_type',
        'from_seen',
        'to_id',
        'to_type',
        'to_seen'
    ];


    public function message(){
        return $this->hasMany('App\Models\ChatMessages','chat_conversation_id');
    }

    public function lastMessage(){
        return $this->hasOne('App\Models\ChatMessages','chat_conversation_id')->orderByDesc('id');
    }

    public function from(){
        return $this->morphTo();
    }

    public function to(){
        return $this->morphTo();
    }


    public function isSeen($myModel,$myModelID){

//         CALL `getCountUnseenMsgs`('1', 'App\\Models\\Staff', '1', @p3, @p4);
//         SELECT @p3 AS `countUnseen`, @p4 AS `lastMID`;

        $result = $this->hasOne('App\Models\ChatConversationSeen','chat_conversation_id')
            ->where(function($query) use ($myModel,$myModelID){
                $query->where('model_type',$myModel)
                    ->where('model_id',$myModelID);
            })
            ->whereRaw("(SELECT `id` FROM `chat_messages` WHERE `chat_messages`.`chat_conversation_id` = `chat_conversation_seen`.`chat_conversation_id` ORDER BY `id` DESC LIMIT 1) = `last_chat_message_id`")
            ->first();

        if($result){
            return true;
        }else{
            return false;
        }
    }


    public function countUnseen($modelType,$modelID){
        DB::select("CALL `getCountUnseenMsgs`(:ChatConversationID, :modelType, :modelID, @p3, @p4);",[
            'ChatConversationID'    => $this->id,
            'modelType'             => $modelType,
            'modelID'               => $modelID
        ]);
        $countUnseen = DB::select("SELECT @p3 AS `countUnseen`;");

        return $countUnseen[0]->countUnseen;
    }




    public function countUnseenMsgs($modelType,$modelID){
        $data = $this->hasOne();
    }


    public function getModelPosition($modelType,$modelID){
        if($this->from_type == $modelType && $this->from_id == $modelID){
            return 'from';
        }elseif($this->to_type == $modelType && $this->to_id == $modelID){
            return 'to';
        }

        return false;
    }

}