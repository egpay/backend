<?php

namespace App\Modules\System;

use App\Models\ChatConversation;
use App\Models\ChatConversationSeen;
use App\Models\Staff;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Form;
use Auth;
use DB;

class ChatController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }

    public function index(Request $request){
        $result = ChatConversation::orderBy('updated_at','DESC')
            ->orderBy('to_type','DESC')
            ->with(['lastMessage','from','to'])
            ->paginate(15);

        $this->viewData['result'] = $result;

        return $this->view('chat.index',$this->viewData);
    }

    public function getConversation($ID,Request $request){

        $data = ChatConversation::with(['from','to'])->findOrFail($ID);

        $returnData = [];
        $returnData['conversation'] = $data->toArray();
        $returnData['conversation']['created_at'] = $data->created_at->diffForHumans();
        $returnData['conversation']['from'] = arrayGetOnly($returnData['conversation']['from'],['id','firstname','lastname','image']);
        $returnData['conversation']['to'] = arrayGetOnly($returnData['conversation']['to'],['id','firstname','lastname','image']);
        $getMessages = $data->message()->with('model')->orderByDesc('id')->paginate(10);
        $dataRows = [];

        foreach($getMessages as $key => $value){
            $dataRows[$key] = $value->toArray();
            $dataRows[$key]['user']['id'] = $value->model_id;
            $dataRows[$key]['user']['name'] = $value->model->firstname.' '.$value->model->lastname;
            $dataRows[$key]['user']['avatar'] = $value->model->avatar;
        }

        krsort($dataRows);
        $dataRows = array_values($dataRows);
        $getMessages = $getMessages->toArray();
        $getMessages['data'] = $dataRows;
        $returnData['messages'] = $getMessages;

        $accessID = md5(str_random(20).uniqid().str_random(50).(time()*rand()));

        if(!$request->page || $request->page == '1') {
            Auth::user()->chat_socket_access()->create([
                'id' => $accessID,
                'chat_conversation_id' => $returnData['conversation']['id']
            ]);
        }


        $seenData = [];
        if(!empty($getMessages['data'])){
            $seenData = ChatConversationSeen::whereIn('last_chat_message_id',array_column($getMessages['data'],'id'))
                ->with('model')->get();
            $seenData = $seenData->groupBy('last_chat_message_id')->toArray();
            if(!$request->page || $request->page == '1') {
                $this->setSeenConversation($ID, last($getMessages['data'])['id']);

                if($data->from_type == Auth::user()->modelPath && $data->from_id == Auth::id()){
                    $data->update(['from_seen'=> 'yes']);
                }elseif($data->to_type == Auth::user()->modelPath && $data->to_id == Auth::id()){
                    $data->update(['to_seen'=> 'yes']);
                }

            }
        }

        $returnData['seenData'] = $seenData;
        $returnData['accessID'] = $accessID;

        return $returnData;
    }

    private function setSeenConversation($ID,$lastMessageID){
        $conversation = ChatConversationSeen::where('chat_conversation_id',$ID)
            ->where('last_chat_message_id',$lastMessageID)
            ->where('model_type','App\Models\Staff')
            ->where('model_id',Auth::id())
            ->first();

        if(!$conversation){
            Auth::user()
                ->conversation_seen()
                ->where('chat_conversation_id',$ID)
                ->where('model_type','App\Models\Staff')
                ->where('model_id',Auth::id())
                ->delete();

            Auth::user()
                ->conversation_seen()
                ->create([
                    'chat_conversation_id'=> $ID,
                    'last_chat_message_id'=> $lastMessageID
                ]);
        }

    }



}
