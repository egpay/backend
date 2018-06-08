<?php

namespace App\Modules\Merchant;

use App\Models\EmailReceiver;
use App\Models\MerchantStaff;
use App\Models\SystemTicket;
use Illuminate\Http\Request;
use App\Models\EmailStar;
use App\Http\Requests\SystemTicketFormRequest;
use Auth;
use Illuminate\Support\Facades\DB;

class MerchantMailController extends MerchantController
{
    protected $viewData;
    public function index(Request $request){
        $this->viewData['pageTitle'] = __('Email');

        $staffID = Auth::id();
        $inboxCount = MerchantStaff::email_receive($staffID)
            ->select(\DB::raw("COUNT(*) as `count`"))
            ->whereNull('seen')
            ->first();

        $this->viewData['inboxCount'] = $inboxCount->count;

        $type = $request->type;
        if($type == 'sent'){
            $type = 'sent';
            $result = MerchantStaff::email_sent($staffID,$request->q)->orderByDesc('id')->paginate();
        }elseif($type == 'star'){
            $type = 'star';
            $result = MerchantStaff::find($staffID)->email_star;

            if($result){
                $result = array_column($result->toArray(),'email_id');
                $result = SystemTicket::whereIn('id',$result)->orderByDesc('id')->paginate();
            }else{
                $result= [];
            }
        }else{
            $type = 'inbox';
            $result = MerchantStaff::email_receive($staffID,$request->q)->orderByDesc('id')->paginate();
        }

        $this->viewData['type'] = $type;
        $this->viewData['result'] = $result;

        return $this->view('mail.inbox',$this->viewData);
    }


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

        $result = SystemTicket::select(['email.*','email_receiver.receivermodel_id','email_receiver.receivermodel_type','email_receiver.star','email_receiver.seen'])
            ->where('email.id',$id)
            ->where(function($query) use($staffID){
                $query->where(function($query) use($staffID) {
                    $query->where('email.sendermodel_type','App\\Models\\MerchantStaff');
                    $query->where('email.sendermodel_id',$staffID);
                })
                    ->orWhere(function($query) use($staffID) {
                        $query->where(function($query) use($staffID){
                            $query->where('email_receiver.receivermodel_type','App\\Models\\MerchantStaff');
                            $query->where(function($query) use($staffID) {
                                $query->where('email_receiver.receivermodel_id',$staffID);
                            });
                        })
                        ->orWhere(function($query) use($staffID){
                            $query->where('email_receiver.receivermodel_type','App\\Models\\Merchant');
                            $merchant = MerchantStaff::find($staffID)->merchant;
                            $query->where(function($query) use($merchant) {
                                $query->where('email_receiver.receivermodel_id',$merchant->id);
                            });
                        });
                    });
            })
            ->leftjoin('email_receiver','email_receiver.email_id','=','email.id')
            ->first();


        if(!$result){
            return ['status'=> false,'msg'=>__('You Can\'t access this Email')];
        }


        if($request->star == 'true'){
            $getStarEmail = EmailStar::where('model_type',get_class(Auth::user()))
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

        if($result->seen == null && ($result->receivermodel instanceof Staff || $result->receivermodel instanceof Merchant) ){
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

        $AllReceivers = $result->receiver()->get(['receivermodel_id','receivermodel_type']);
        $Receivers = [];
        foreach($AllReceivers as $oneReciver){
            $Receivers[] = (new $oneReciver->receivermodel_type)->find($oneReciver->receivermodel_id);
        }



        $this->viewData['receivers'] = array_filter($Receivers);

        $this->viewData['parent'] = parent($result->parent);
        $this->viewData['result'] = $result;
        return ['status'=> true,'html'=>$this->view('mail.show',$this->viewData)->render()];

    }

    public function edit()
    {
        return back();
    }

    public function update()
    {
        return back();
    }

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


    public function create(){
        return back();
    }


    public function store(SystemTicketFormRequest $request){
        $theRequest = $request->all();
        if($request->file('file')) {
            $theRequest['file'] = $request->file->store('system-tickets/'.date('y').'/'.date('m'));
        }

        if($request->reply_to){
            $dataFromReply = SystemTicket::findOrFail($request->reply_to);

            if($dataFromReply->sendermodel_type == 'App\Models\Staff'){
                $receiver['receivermodel_type'] = $dataFromReply->receivermodel_type;
                $receiver['receivermodel_id']   = $dataFromReply->receivermodel_id;
            }else{
                $receiver['receivermodel_type'] = $dataFromReply->sendermodel_type;
                $receiver['receivermodel_id']   = $dataFromReply->sendermodel_id;
            }

            $theRequest['parent_id'] = $request->reply_to;
        }else{
            if($request->receivermodel_id){
                $receiver['receivermodel_type'] = 'App\Models\MerchantStaff';
                $receiver['receivermodel_id']   = $request->receivermodel_id;
            }else{
                $receiver['receivermodel_type'] = 'App\Models\Merchant';
                $receiver['receivermodel_id']   = $request->merchant_id;
            }
        }

        $theRequest['sendermodel_type'] = get_class(Auth::user());
        $theRequest['sendermodel_id'] = Auth::id();


        $GLOBALS['status'] = false;
        DB::transaction(function () use ($theRequest,$receiver) {
            if(!$Email = SystemTicket::create($theRequest))
                return false;
            $receiver['email_id'] = $Email->id;
            if(!$Email->receiver()->create($receiver))
                return false;
            $GLOBALS['status'] = true;
        });


        if($GLOBALS['status'])
            return ['status'=>true,'msg'=> 'Email is sent successfully'];
        else{
            return ['status'=>false,'msg'=> 'Can\'t Sent Email'];
        }
    }
}
