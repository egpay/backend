<?php

namespace App\Modules\System;

use App\Events\SenderEvent;
use App\Models\Sender;
use Illuminate\Http\Request;
use App\Http\Requests\SenderFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;
use GuzzleHttp\Client;

class SenderController extends SystemController
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        if($request->isDataTable){

            $eloquentData = Sender::select([
                'sender.id',
                'sender.type',
                'sender.send_to',
                "sender.created_at",
                'sender.status',
                'sender.staff_id',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
            ])
                ->join('staff','staff.id','=','sender.staff_id');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'sender.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('sender.id', '=',$request->id);
            }

            if($request->type){
                orWhereByLang($eloquentData,'sender.type',$request->type);
            }

            if($request->send_to){
                $eloquentData->where('sender.send_to','LIKE',"%".$request->send_to."%");
            }

            if($request->subject){
                $eloquentData->where('sender.subject', 'LIKE',"%".$request->subject."%");
            }

            if($request->status){
                $eloquentData->where('sender.status','=',$request->status);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('type','{{$type}}')
                ->addColumn('send_to',function($data){
                    if($data->type == 'email'){
                        return "<a href='mailto:".$data->send_to."'>".$data->send_to."</a>";
                    }else{
                        return "<a href='tel:".$data->send_to."'>".$data->send_to.'</a>';
                    }
                })
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('status','{{$status}}')

                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='urlIframe(\"".route('system.sender.show',$data->id)."\")'><i class=\"ft-eye\"></i></button>";
                })

                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Type'),__('Send To'),__('Created At'),__('By'),__('status'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Fast Send Log')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Send Log');
            }else{
                $this->viewData['pageTitle'] = __('Send Log');
            }

            return $this->view('sender.index',$this->viewData);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SenderFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('file')) {
            $theRequest['file'] = $request->file->store('sender/'.date('y').'/'.date('m'));
        }


        if($theRequest['type'] == 'sms'){
            $theRequest['body'] = $theRequest['sms_body'];
        }else{
            $theRequest['body'] = $theRequest['email_body'];
        }

        $theRequest['staff_id'] = Auth::id();
        $theRequest['status']   = 'request';

        if($senderData = Sender::create($theRequest)) {
            event(new SenderEvent($senderData));
            return ['status' => true, 'msg' => __('Message added to queue')];
        }else{
            return ['status'=> false,'msg'=>__('Sorry Couldn\'t Send '.$request->type)];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(Sender $sender){
        $this->viewData['result'] = $sender;
        return $this->view('sender.show',$this->viewData);

    }

}
