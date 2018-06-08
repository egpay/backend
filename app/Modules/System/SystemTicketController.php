<?php

namespace App\Modules\System;

use App\Models\EmailStar;
use App\Models\Staff;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Auth;
use App\Models\SystemTicket;
use App\Http\Requests\SystemTicketFormRequest;
use App\Models\Merchant;
use App\Models\MerchantStaff;

class SystemTicketController extends SystemController
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
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Tickets'),
            'url'=> route('system.system-ticket.index')
        ];
        $this->viewData['pageTitle'] = __('Email');


        $staffID = Auth::id();
        $inboxCount = Staff::email_receive($staffID)
            ->select(\DB::raw("COUNT(*) as `count`"))
            ->whereNull('seen')
            ->first();

        $this->viewData['inboxCount'] = $inboxCount->count;

        $type = $request->type;
        if($type == 'sent'){
            $type = 'sent';
            $result = Staff::email_sent($staffID,$request->q)->orderByDesc('id')->paginate();
        }elseif($type == 'star'){
            $type = 'star';
            $result = Staff::find($staffID)->email_star;

            if($result){
                $result = array_column($result->toArray(),'email_id');
                $result = SystemTicket::whereIn('id',$result)->orderByDesc('id')->paginate();
            }else{
                $result= [];
            }
        }else{
            $type = 'inbox';
            $result = Staff::email_receive($staffID,$request->q)->orderByDesc('id')->paginate();
        }

        $this->viewData['type'] = $type;
        $this->viewData['result'] = $result;



        return $this->view('system-ticket.index',$this->viewData);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SystemTicketFormRequest $request)
    {

        $theRequest = $request->all();
        if($request->file('file')) {
            $theRequest['file'] = $request->file->store('system-tickets/'.date('y').'/'.date('m'));
        }

        if($request->reply_to){
            $dataFromReply = SystemTicket::findOrFail($request->reply_to);

            if($dataFromReply->sendermodel_type == 'App\Models\Staff'){
                $theRequest['receivermodel_type'] = $dataFromReply->receivermodel_type;
                $theRequest['receivermodel_id']   = $dataFromReply->receivermodel_id;
            }else{
                $theRequest['receivermodel_type'] = $dataFromReply->sendermodel_type;
                $theRequest['receivermodel_id']   = $dataFromReply->sendermodel_id;
            }

            $theRequest['parent_id'] = $request->reply_to;
        }else{

            if($request->send_to_type == 'merchant'){
                if($request->receivermodel_id){
                    $theRequest['receivermodel_type'] = 'App\Models\MerchantStaff';
                    $theRequest['receivermodel_id']   = $request->receivermodel_id;
                }else{
                    $theRequest['receivermodel_type'] = 'App\Models\Merchant';
                    $theRequest['receivermodel_id']   = $request->merchant_id;
                }
            }else{
                $theRequest['receivermodel_type'] = 'App\Models\Staff';
                $theRequest['receivermodel_id']   = $request->staff_id;
            }


        }


        $theRequest['sendermodel_type'] = 'App\Models\Staff';
        $theRequest['sendermodel_id'] = Auth::id();


        if(SystemTicket::create($theRequest))
            return ['status'=>true,'msg'=> 'Email is sent successfully'];
        else{
            return ['status'=>false,'msg'=> 'Can\'t Sent Email'];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id,Request $request){

        // Parent function
        function parent($parentData) {
            static $data = [];
            if($parentData){
                $parentData->senderType = 'egpay';
                if($parentData->sendermodel instanceof Staff){
                    $parentData->senderType = 'staff';
                }elseif($parentData->sendermodel instanceof Merchant){
                    $parentData->senderType = 'merchant';
                }elseif($parentData->sendermodel instanceof MerchantStaff){
                    $parentData->senderType = 'merchant_staff';
                }


                // Receiver Type
                $parentData->receiverType = 'egpay';
                if($parentData->receivermodel instanceof Staff){
                    $parentData->receiverType = 'staff';
                }elseif($parentData->receivermodel instanceof Merchant){
                    $parentData->receiverType = 'merchant';
                }elseif($parentData->receivermodel instanceof MerchantStaff){
                    $parentData->receiverType = 'merchant_staff';
                }

                $data[] = $parentData;
                return parent($parentData->parent);
            }
            return $data;
        }

        $staffID = Auth::id();
        $result = SystemTicket::where('id',$id)
            ->where(function($query) use($staffID){
                $query->where(function($query) use($staffID) {
                    $query->where('sendermodel_type','App\\Models\\Staff');
                    $query->where(function($query) use($staffID) {
                        $query->where('sendermodel_id',$staffID);
                        $query->orWhereNull('sendermodel_id');
                    });
                })
                    ->orWhere(function($query) use($staffID) {
                        $query->where('receivermodel_type','App\\Models\\Staff');
                        $query->where(function($query) use($staffID) {
                            $query->where('receivermodel_id',$staffID);
                            $query->orWhereNull('receivermodel_id');
                        });
                    });
            })->first();

        if(!$result){
            return ['status'=> false,'msg'=>__('You Can\'t access this Email')];
        }


        if($request->star == 'true'){
            $getStarEmail = EmailStar::where('model_type','App\\Models\\Staff')
                ->where('model_id',$staffID)
                ->where('email_id',$id)
                ->first();
            if($getStarEmail){
                $getStarEmail->delete();
                return ['status'=>true,'msg'=>'This Email has ben Un-Starred'];
            }else{


                Auth::user()->email_star()->save(new EmailStar([
                    'email_id'=> $id
                ]));
                return ['status'=>true,'msg'=>'This Email has ben Starred'];
            }
        }

        if($result->seen == null && $result->receivermodel instanceof Staff && $result->receivermodel->id == $staffID){
            $result->update(['seen'=> Carbon::now(),'seen_id'=>$staffID]);
        }

        // Sender Type
        $result->senderType = 'egpay';
        if($result->sendermodel instanceof Staff){
            $result->senderType = 'staff';
        }elseif($result->sendermodel instanceof Merchant){
            $result->senderType = 'merchant';
        }elseif($result->sendermodel instanceof MerchantStaff){
            $result->senderType = 'merchant_staff';
        }

        $this->viewData['senderType'] = $result->senderType;


        // Receiver Type
        $result->receiverType = 'egpay';
        if($result->receivermodel instanceof Staff){
            $result->receiverType = 'staff';
        }elseif($result->receivermodel instanceof Merchant){
            $result->receiverType = 'merchant';
        }elseif($result->receivermodel instanceof MerchantStaff){
            $result->receiverType = 'merchant_staff';
        }

        $this->viewData['receiverType'] = $result->receiverType;

        $this->viewData['parent'] = parent($result->parent);
        $this->viewData['result'] = $result;

        return ['status'=> true,'html'=> $this->view('system-ticket.show',$this->viewData)->render()];

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(SystemTicket $system_ticket,Request $request)
    {
        $id = $system_ticket->id;
        $staffID = Auth::id();
        $result  = SystemTicket::where('id',$id)
            ->where(function($query) use($staffID){
                $query->where(function($query) use($staffID) {
                    $query->where('sendermodel_type','App\\Models\\Staff');
                    $query->where(function($query) use($staffID) {
                        $query->where('sendermodel_id',$staffID);
                        $query->orWhereNull('sendermodel_id');
                    });
                })
                    ->orWhere(function($query) use($staffID) {
                        $query->where('receivermodel_type','App\\Models\\Staff');
                        $query->where(function($query) use($staffID) {
                            $query->where('receivermodel_id',$staffID);
                            $query->orWhereNull('receivermodel_id');
                        });
                    });
            })->first();

        if(!$result){
            return ['status'=> false,'msg'=> __('You can\'t Delete this email')];
        }
        // Delete Data
        $system_ticket->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Email has been deleted successfully')];
        }else{
            redirect()
                ->route('system.system-ticket.index')
                ->with('status','success')
                ->with('msg',__('This Email has been deleted'));
        }
    }


}
