<?php

namespace App\Modules\System;

use App\Models\AudioMessage;
use App\Models\NewsCategory;
use Illuminate\Http\Request;
use App\Http\Requests\NewsCategoryFormRequest;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class AudioMessageController extends SystemController
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

            $eloquentData = AudioMessage::leftJoin('staff','staff.id','=','audio_messages.seenby_id')
                ->select([
                    'audio_messages.id',
                    'audio_messages.msgsendermodel_id',
                    'audio_messages.msgsendermodel_type',
                    'audio_messages.path',
                    'audio_messages.seen',
                    'audio_messages.seenby_id',
                    'audio_messages.created_at',
                    \DB::raw("CONCAT(staff.firstname,' ',staff.lastname) as `staff_name`")
                ])
                ->with('msgsendermodel');

            whereBetween($eloquentData,'audio_messages.created_at',$request->created_at1,$request->created_at2);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            if($request->id){
                $eloquentData->where('audio_messages.id','=',$request->id);
            }

            if($request->seen == 'no'){
                $eloquentData->whereNull('audio_messages.seen');
            }elseif($request->seen == 'yes'){
                $eloquentData->whereNotNull('audio_messages.seen');
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('msgsendermodel',function($data){
                    return adminDefineUser($data->msgsendermodel_type,$data->msgsendermodel_id,$data->msgsendermodel->firstname.' '.$data->msgsendermodel->lastname);
                })
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('seen',function($data){
                    if($data->seen !== null){
                        return '<a href="'.route('system.staff.show',$data->seenby_id).'" target="_blank">'.$data->staff_name.'</a><br />'.$data->seen->diffForHumans();
                    }
                    return '--';
                })
                ->addColumn('action',function($data){
                    return "
                        <button class=\"btn btn-primary\" type=\"button\" onclick=\"urlIframe('".route('system.audio-messages.show',['ID'=> $data->id])."')\"><i class=\"ft-eye\"></i></button>
                    ";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Created By'),
                __('Created At'),
                __('Seen By'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Audio Messages')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Audio Messages');
            }else{
                $this->viewData['pageTitle'] = __('Audio Messages');
            }

            return $this->view('audio-message.index',$this->viewData);
        }
    }

    public function show($ID){
        $data = AudioMessage::findOrFail($ID);

        if($data->seen == null){
            $data->update([
                'seen'=> date('Y-m-d H:i:s'),
                'seenby_id'=> Auth::id()
            ]);
        }

        $this->viewData['result'] = $data;
        return $this->view('audio-message.show',$this->viewData);
    }

}
