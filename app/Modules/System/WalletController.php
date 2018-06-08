<?php

namespace App\Modules\System;

use App\Http\Requests\transferMoneyMainWalletsFormRequest;
use App\Libs\WalletData;
use App\Models\MainWallets;
use App\Models\Merchant;
use App\Models\RequestRechargeWallet;
use App\Models\Staff;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Form;
use Auth;
use Notification;
use App\Http\Requests\transferMoneySupervisorFormRequest;
use App\Http\Requests\transferMoneyStaffFormRequest;
use App\Http\Requests\transferMoneyWalletsFormRequest;
use App\Http\Requests\transferMoneyTwoWalletsFormRequest;

class WalletController extends SystemController{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }



    public function mainWallets(){
        $data = MainWallets::with('wallet')->get();
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Main Wallets')
        ];

        $this->viewData['pageTitle'] = __('Main Wallets');

        $this->viewData['result'] = $data;

        return $this->view('wallet.main-wallets',$this->viewData);

    }


    public function transferMoneySupervisor(){
        if(!Auth::user()->is_supervisor()){
            abort(404);
        }

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallet'),
            'url'=> route('system.wallet.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transfer Money'),
        ];

        $this->viewData['pageTitle'] =  __('Transfer Money');

        // ----- Wallet

        $this->viewData['postRoute']    = 'system.wallet.transfer-money-supervisor.post';
        $this->viewData['sendToStaff']  = Auth::user()->managed_staff;
        // ----- Wallet
        return $this->view('wallet.transfer-money',$this->viewData);
    }
    public function transferMoneySupervisorPost(transferMoneySupervisorFormRequest $request){
        if(!Auth::user()->is_supervisor()){
            abort(404);
        }

        $incomeData = $request->only(['send_to','amount']);
        $staffPaymentWallet = Wallet::findOrFail($incomeData['send_to']);
        
        if($staffPaymentWallet->type != 'payment' || !Auth::user()->paymentWallet){
            return back()->with('transactionStatus',['status'=>false,'error_code'=> 1]);
        }


        WalletData::makeTransactionWithoutModel(true);
        $transfer = WalletData::makeTransaction(
            $incomeData['amount'],
            'wallet',
            Auth::user()->paymentWallet->id,
            $staffPaymentWallet->id,
            null,
            null,
            Auth::user()->modelPath,
            Auth::id(),
            'paid'
        );

       return  back()->with('transactionStatus',$transfer);
    }

    public function transferMoneyStaff(){
        if(Auth::user()->is_supervisor()){
            abort(404);
        }

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallet'),
            'url'=> route('system.wallet.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transfer Money'),
        ];

        $this->viewData['pageTitle'] =  __('Transfer Money');

        // ----- Wallet
        $this->viewData['postRoute']    = 'system.wallet.transfer-money-staff.post';
        $this->viewData['sendToStaff']  = Auth::user()->merchant;
        // ----- Wallet
        return $this->view('wallet.transfer-money',$this->viewData);
    }
    public function transferMoneyStaffPost(transferMoneyStaffFormRequest $request){
        if(Auth::user()->is_supervisor()){
            abort(404);
        }

        $incomeData = $request->only(['send_to','amount']);

        $merchantPaymentWallet = Wallet::findOrFail($incomeData['send_to']);

        if($merchantPaymentWallet->type != 'payment' || !Auth::user()->paymentWallet){
            return back()->with('transactionStatus',['status'=>false,'error_code'=> 1]);
        }

        WalletData::makeTransactionWithoutModel(true);
        $transfer = WalletData::makeTransaction(
            $incomeData['amount'],
            'wallet',
            Auth::user()->paymentWallet->id,
            $merchantPaymentWallet->id,
            null,
            null,
            Auth::user()->modelPath,
            Auth::id(),
            'paid'
        );

        return back()->with('transactionStatus',$transfer);
    }

    public function transferMoneyMainWallets(Request $request){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Main Wallet'),
            'url'=> route('system.wallet.main-wallets')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transfer Money'),
        ];

        $this->viewData['pageTitle'] =  __('Transfer Money');

        // ----- Wallet
        $this->viewData['postRoute'] = 'system.wallet.transfer-money-main-wallets.post';
        $this->viewData['walletIN']  = MainWallets::where('transfer_in','yes')->get(['id','name']);
        $this->viewData['walletOUT'] = MainWallets::where('transfer_out','yes')->get(['id','name']);

        $getAllSupervisorWallet = Wallet::join('staff','staff.id','=','wallet.walletowner_id')
            ->join('permission_groups','permission_groups.id','=','staff.permission_group_id')
            ->where('wallet.walletowner_type','=','App\Models\Staff')
            ->where('wallet.type','=','payment')
            ->where('permission_groups.is_supervisor','=','yes')
            ->get([
                'wallet.id as wallet_id',
                'wallet.type as wallet_type',
                'wallet.balance as wallet_balance',
                'staff.id as staff_id',
                \DB::raw('CONCAT("#ID:",staff.id," ",staff.firstname," ",staff.lastname) as `staff_name`')
            ])
            ->toArray();

        $newSupervisorWalletData = [];
        if($getAllSupervisorWallet){
            foreach ($getAllSupervisorWallet as $key => $value){
                $newSupervisorWalletData[$value['staff_name']][$value['wallet_id']] = ucfirst($value['wallet_type']) .' ('. amount($value['wallet_balance'],true).')';
            }
        }

        $this->viewData['sendTo'] = $newSupervisorWalletData;


        // ----- Wallet

        return $this->view('wallet.transfer-money-main-wallets',$this->viewData);
    }
    public function transferMoneyMainWalletsPost(transferMoneyMainWalletsFormRequest $request){

        if(!staffCan('transfer-money-main-wallets-without-approval')){
            $RequestRechargeWallet = RequestRechargeWallet::create([
                'staff_id'=> Auth::id(),
                'transfer_type' => ($request->transfer_type == 'transfer_in') ? 'in' : 'out',
                'from_wallet_id' => $request->main_wallet_id,
                'to_wallet_id'=> $request->send_to,
                'amount' => ($request->transfer_type == 'transfer_in') ? $request->amount_in : $request->amount_out
            ]);

            $transactionStatus = 'pending';
        }else{
            $transactionStatus = 'paid';
        }

        if($request->transfer_type == 'transfer_in'){
            WalletData::makeTransactionWithoutModel(true);
            WalletData::makeTransactionWithoutBalance(true);
            $transfer = WalletData::makeTransaction(
                $request->amount_in,
                'wallet',
                setting('main_wallet_id'),
                $request->main_wallet_id,
                null,
                null,
                Auth::user()->modelPath,
                Auth::id(),
                $transactionStatus
            );
        }else{
            WalletData::makeTransactionWithoutModel(true);
            WalletData::makeTransactionWithoutBalance(true);
            $transfer = WalletData::makeTransaction(
                $request->amount_out,
                'wallet',
                $request->main_wallet_id,
                $request->send_to,
                null,
                null,
                Auth::user()->modelPath,
                Auth::id(),
                $transactionStatus
            );
        }

        if($transactionStatus == 'pending'){

            $RequestRechargeWallet->update(['transaction_id'=>$transfer->id]);

            $notifyStaff = Staff::join('permission_groups','permission_groups.id','=','staff.permission_group_id')
                ->join('permissions','permissions.permission_group_id','=','permission_groups.id')
                ->where('permissions.route_name','transfer-money-main-wallets-without-approval')
                ->get();

            if($notifyStaff->isNotEmpty()){
                Notification::send($notifyStaff,
                    new UserNotification([
                        'title'         => 'Request Transfare Money',
                        'description'   => ($request->transfer_type == 'transfer_in') ? amount($request->amount_in) : amount($request->amount_out).' '.__('By').' '.Auth::user()->firstname.' '.Auth::user()->lastname,
                        'url'           => route('system.wallet.requestRechargeWallet')
                    ])
                );
            }
        }
        return  back()->with('transactionStatus',$transfer);
    }

    public function requestRechargeWallet(Request $request){
        if($request->isDataTable){

            $eloquentData = RequestRechargeWallet::with(['staff','from_wallet','to_wallet','action_staff']);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }



            if($request->transfer_type){
                $eloquentData->where('transfer_type',$request->transfer_type);
            }

            if($request->from_wallet_id){
                $eloquentData->where('from_wallet_id',$request->from_wallet_id);
            }

            if($request->to_wallet_id){
                $eloquentData->where('to_wallet_id',$request->to_wallet_id);
            }

            if($request->amount){
                whereBetween($eloquentData,'amount',$request->amount1,$request->amount2);
            }

            if($request->status){
                $eloquentData->where('status',$request->status);
            }

            if($request->action_staff_id){
                $eloquentData->where('action_staff_id',$request->action_staff_id);
            }

            // Supervisor
            if(!staffCan('transfer-money-main-wallets-without-approval')){
                $eloquentData->where('staff_id', '=',Auth::id());
            }else{
                if($request->staff_id){
                    $eloquentData->where('staff_id', '=',$request->staff_id);
                }
            }



            // Get One item in JSON Format
            if($request->returnAjax){
                return $eloquentData->first();
            }



            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL->select([DB::raw('SUM(`amount`) as `total`')])->first()->total;


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('transfer_type','{{__(ucfirst($transfer_type))}}')
                ->addColumn('from_wallet_id',function($data){
                    $Model  = explode('\\',$data->from_wallet->walletowner_type);
                    $return = ucwords(str_replace('_', ' ', end($Model)));
                    // ----

                    if($data->to_wallet){
                        $ModelTO  = explode('\\',$data->to_wallet->walletowner_type);
                        $returnTO = ucwords(str_replace('_', ' ', end($ModelTO)));
                        $returnAppend = ' <i class="fa fa-long-arrow-right"></i> ['.$returnTO.'] '.getWalletOwnerName($data->to_wallet,$this->systemLang);
                    }else{
                        $returnAppend = '';
                    }

                    return '['.$return.'] '.getWalletOwnerName($data->from_wallet,$this->systemLang).$returnAppend;
                })
                ->addColumn('amount','{{amount($amount)}}')
                ->addColumn('staff_id', function ($data){
                    return '<a href="'.route('system.staff.show',$data->staff_id).'" target="_blank">'.$data->staff->firstname.' '.$data->staff->lastname.'</a>'
                        .'<br>'.$data->created_at->diffForHumans();
                })
                ->addColumn('status',function ($data){
                    if($data->status != 'request'){
                        return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('Status').'</td>
                                        <td>'.statusColor($data->status).'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('By').'</td>
                                        <td><a href="'.route('system.staff.show',$data->action_staff->id).'" target="_blank">'.$data->action_staff->firstname.' '.$data->action_staff->lastname.'</a></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">'.$data->updated_at->diffForHumans().'</td>
                                    </tr>
                                </tbody>
                            </table>';
                    }
                    return statusColor($data->status);
                })
                ->addColumn('action',function($data){
                    if($data->status == 'request'){
                        return "<button class=\"btn btn-primary action-id-".$data->id."\" type=\"button\" onclick='take_action(".$data->id.",$(this))'><i class=\"ft-cog\"></i></button>";
                    }else{
                        return '--';
                    }
                })
                ->addColumn('total',function ($data) use($SYSTEM_TOTAL){
                    return amount($SYSTEM_TOTAL);
                })
                ->make(true);
        }elseif($request->takeAction){
            $id             = $request->id;
            $status         = strtolower($request->status);
            $action_comment = $request->action_comment;

            $data = RequestRechargeWallet::where('id',$id)
                ->where('status','request')
                ->first();

            if(!$data){
                return ['status'=>false,'msg'=>__('You Can\'t Update This Request'),'id'=>$id];
            }elseif(!in_array($status,['approved','disapproved'])){
                return ['status'=>false,'msg'=>__('Please select status from approved or disapproved'),'id'=>$id];
            }


            $tStatus = ($status == 'approved') ? 'paid' : 'reverse';
            $WalletDataStatus = WalletData::changeTransactionStatus($data->transaction_id,$tStatus,Auth::user()->modelPath,Auth::id(),$action_comment);
            if($WalletDataStatus === true){
                $data->update([
                    'status'=> $status,
                    'action_staff_id'=> Auth::id(),
                    'action_comment'=> $action_comment
                ]);
                return ['status'=>true,'msg'=> __('this request has been :status successfully',['status'=>$status]),'id'=>$id];
            }

            return ['status'=>false,'msg'=> __('Unknown Error Code: :code',['code'=>$WalletDataStatus['error_code']]),'id'=>$id];

        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Transfer Type'),
                __('Wallet'),
                __('Amount'),
                __('Created By'),
                __('Status'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Recharge Requests')
            ];

            $this->viewData['pageTitle'] = __('Recharge Requests');

            $this->viewData['walletUserType'] = WalletData::$ownerType;

            return $this->view('wallet.transferMoneyMainWalletsPost',$this->viewData);
        }
    }


    public function transferMoneyWallets(){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallet'),
            'url'=> route('system.wallet.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transfer Money'),
        ];

        $this->viewData['pageTitle'] =  __('Transfer Money');

        return $this->view('wallet.transfer-money-wallets',$this->viewData);
    }
    public function transferMoneyWalletsPost(transferMoneyWalletsFormRequest $request){
        $incomeData = $request->only(['send_to','amount']);

        $disabledWallets = setting('disabled_wallets');
        $getPaymentWalletSendTo = Wallet::findOrFail($incomeData['send_to']);

        if(
            $getPaymentWalletSendTo->type != 'payment'
            || !Auth::user()->paymentWallet
            || in_array($getPaymentWalletSendTo->id,explode("\n",$disabledWallets))
        ){
            return back()->with('transactionStatus',['status'=>false,'error_code'=> 1]);
        }

        WalletData::makeTransactionWithoutModel(true);
        $transfer = WalletData::makeTransaction(
            $incomeData['amount'],
            'wallet',
            Auth::user()->paymentWallet->id,
            $getPaymentWalletSendTo->id,
            null,
            null,
            Auth::user()->modelPath,
            Auth::id(),
            'paid'
        );

        return back()->with('transactionStatus',$transfer);
    }

    // Critical
    public function transferMoneyTwoWallets(){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallet'),
            'url'=> route('system.wallet.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transfer Money'),
        ];

        $this->viewData['pageTitle'] =  __('Transfer Money Between To Wallets');

        return $this->view('wallet.transfer-money-wallets',$this->viewData);
    }
    public function transferMoneyTwoWalletsPost(transferMoneyTwoWalletsFormRequest $request){

        $incomeData      = $request->only(['send_from','send_to','amount']);

        $sendFromWallet = Wallet::findOrFail($incomeData['send_from']);
        $sendToWallet   = Wallet::findOrFail($incomeData['send_to']);

        if($sendFromWallet->type != $sendToWallet->type){
            return back()->with('transactionStatus',['status'=>false,'error_code'=> 1]);
        }elseif($sendFromWallet->walletowner instanceof \App\Models\MainWallets){
            return back()->with('transactionStatus',['status'=>false,'error_code'=> 2]);
        }

        WalletData::makeTransactionWithoutModel(true);
        $transfer = WalletData::makeTransaction(
            $incomeData['amount'],
            'wallet',
            $sendFromWallet->id,
            $sendToWallet->id,
            null,
            null,
            Auth::user()->modelPath,
            Auth::id(),
            'paid'
        );

        return back()->with('transactionStatus',$transfer);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        if($request->isDataTable){

            $eloquentData = Wallet::with('walletowner');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if(($request->type) && in_array($request->type,['payment','e-commerce','loyalty'])){
                $eloquentData->where('type',$request->type);
            }

            if($request->walletowner_type){
                $eloquentData->where('walletowner_type',WalletData::getWalletOwnerTypeModel($request->walletowner_type));
            }

            if($request->walletowner_id){
                $eloquentData->where('walletowner_id',$request->walletowner_id);
            }

            if($request->balance){
                whereBetween($eloquentData,'balance',$request->balance1,$request->balance2);
            }

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){

                $managed_staff_ids = Auth::user()->managed_staff_ids();
                $eloquentData->where(function($query) use($managed_staff_ids){

                    $query->where(function($q1) use ($managed_staff_ids) {
                        $q1->where('walletowner_type',Auth::user()->modelPath);
                        $q1->whereIn('walletowner_id',$managed_staff_ids);
                    });

                    $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                    if($getMerchantIDsByStaffIDs){
                        $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                            $q2->where('walletowner_type',(new Merchant)->modelPath);
                            $q2->whereIn('walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                        });
                    }

                });

            }


            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL->select([DB::raw('SUM(`wallet`.`balance`) as `total`')])->first()->total;

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('type',function($data){
                    if($data->type == 'payment'){
                        $return = '<lable class="label label-warning">';
                    }else{
                        $return = '<lable class="label label-danger">';
                    }

                    return $return.ucfirst($data->type).'</lable>';

                })
                ->addColumn('walletowner_id', function($data){
                    $Model = explode('\\',$data->walletowner_type);
                    $return = ucwords(str_replace('_', ' ', end($Model)));
                    return $return.'<br />'.getWalletOwnerName($data,$this->systemLang);
                })
                ->addColumn('balance','{{amount($balance)}}')
                ->addColumn('updated_at',function($data){
                    if(empty($data->updated_at)){
                        return '--';
                    }
                    return $data->updated_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='location = \"".route('system.wallet.show',$data->id)."\"'><i class=\"ft-eye\"></i></button>";
                })
                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->make(true);
        }elseif($request->walletInfo){
            $return = [];

            $id = $request->walletInfo;
            $wallet = Wallet::findOrFail($id);

            $walletInfoFrom = $request->walletInfoFrom;
            if($walletInfoFrom){
                $fromWallet = Wallet::findOrFail($walletInfoFrom);
                $return['from_name'] = getWalletOwnerName($fromWallet,$this->systemLang);
                if(staffCan('system.wallet.transferMoneyTwoWallets')) {
                    $return['from_balance'] = $fromWallet->balance;
                }
            }

            $return['name'] = getWalletOwnerName($wallet,$this->systemLang);
            if(staffCan('system.wallet.transferMoneyTwoWallets')){
                $return['balance'] = $wallet->balance;
            }

            return $return;
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Wallet Type'),
                __('Owner'),
                __('Balance'),
                __('Last Update'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Wallets')
            ];

            $this->viewData['pageTitle'] = __('Wallets');

            $this->viewData['walletUserType'] = WalletData::$ownerType;

            return $this->view('wallet.index',$this->viewData);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($ID,Request $request){
        $wallet = Wallet::where('id',$ID);

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id())){

            $managed_staff_ids = Auth::user()->managed_staff_ids();

            $wallet->where(function($query) use($managed_staff_ids){

                $query->where(function($q1) use ($managed_staff_ids) {
                    $q1->where('walletowner_type',Auth::user()->modelPath);
                    $q1->whereIn('walletowner_id',$managed_staff_ids);
                });

                $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                if($getMerchantIDsByStaffIDs){
                    $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                        $q2->where('walletowner_type',(new Merchant)->modelPath);
                        $q2->whereIn('walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                    });
                }

            });

        }

        $wallet = $wallet->firstOrFail();


        if($request->isDataTable){
            $eloquentData = $wallet->allTransaction();

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->model_type){
                $eloquentData->where('model_type',WalletData::getModelTypeByModel($request->model_type));
            }

            if($request->model_id){
                $eloquentData->where('model_id',$request->model_id);
            }

            if($request->type){
                $eloquentData->where('type',$request->type);
            }

            if($request->status){
                $eloquentData->where('status',$request->status);
            }

            if($request->amount){
                whereBetween($eloquentData,'amount',$request->amount1,$request->amount2);
            }

            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL->select([DB::raw('SUM(`amount`) as `total`')])->first()->total;

            return Datatables::eloquent($eloquentData)
                ->addColumn('details',function($data){
                    return ' ';
                })
                ->addColumn('id','{{$id}}')
                ->addColumn('model_id',function($data){
                    if(!$data->model){
                        return '--';
                    }else{
                        return WalletData::getModelTypeByModel($data->model_type) . ' ('.$data->model_id.') ';
                    }
                })
                ->addColumn('amount',function($data){
                    return amount($data->amount,true);
                })
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('type', function($data){
                    return __($data->type);
                })
                ->addColumn('status', function($data){
                    return __($data->status);
                })
                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='urlIframe(\"".route('system.wallet.transactions.show',$data->id)."\")'><i class=\"ft-eye\"></i></button>";
                })
                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->make(true);
        }else{

            $this->viewData['breadcrumb'] = [
                [
                    'text'=> __('Home'),
                    'url'=> url('system'),
                ],
                [
                    'text'=> __('Wallet'),
                    'url'=> route('system.wallet.index'),
                ],
                [
                    'text'=> getWalletOwnerName($wallet,$this->systemLang),
                ]
            ];

            $this->viewData['pageTitle'] = __('Show Wallet #ID:').' '.$wallet->id;
            $this->viewData['result'] = $wallet;

            $this->viewData['walletModelType'] = WalletData::$modelType;

            $this->viewData['info'] = [
                'diffBetweenModelsType'=> array_chunk(WalletData::diffBetweenModelsType($wallet->id),2,true),
                'diffBetweenStatusType'=> array_chunk(WalletData::diffBetweenStatusType($wallet->id),2,true)
            ];
            return $this->view('wallet.show',$this->viewData);
        }

    }

    public function transactions(Request $request){

        if($request->isDataTable){
            $eloquentData = WalletTransaction::with(['fromWallet','toWallet','model'])
                ->leftJoin('wallet as w_from','w_from.id','=','transactions.from_id')
                ->leftJoin('wallet as w_to','w_to.id','=','transactions.to_id')
                ->select(['transactions.id','transactions.*']);



            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){

                $managed_staff_ids = Auth::user()->managed_staff_ids();
                $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                $eloquentData->where(function($query) use($getMerchantIDsByStaffIDs, $managed_staff_ids){
                    $query->where(function($query) use($getMerchantIDsByStaffIDs, $managed_staff_ids){
                        $query->where(function($query) use ($getMerchantIDsByStaffIDs, $managed_staff_ids) {
                            $query->where(function($q1) use ($managed_staff_ids) {
                                $q1->where('w_from.walletowner_type',Auth::user()->modelPath);
                                $q1->whereIn('w_from.walletowner_id',$managed_staff_ids);
                            });

                            if($getMerchantIDsByStaffIDs){
                                $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                                    $q2->where('w_from.walletowner_type',(new Merchant)->modelPath);
                                    $q2->whereIn('w_from.walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                                });
                            }
                        });
                    });
                    $query->orWhere(function($query) use($getMerchantIDsByStaffIDs,$managed_staff_ids){
                        $query->where(function($query) use ($getMerchantIDsByStaffIDs,$managed_staff_ids) {
                            $query->where(function($q1) use ($managed_staff_ids) {
                                $q1->where('w_to.walletowner_type',Auth::user()->modelPath);
                                $q1->whereIn('w_to.walletowner_id',$managed_staff_ids);
                            });

                            if($getMerchantIDsByStaffIDs){
                                $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                                    $q2->where('w_to.walletowner_type',(new Merchant)->modelPath);
                                    $q2->whereIn('w_to.walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                                });
                            }
                        });
                    });
                });


            }


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('id', '=',$request->id);
            }

            if($request->model_type){
                if($request->model_type=='transfer')
                    $eloquentData->whereNull('model_type');
                else
                    $eloquentData->where('model_type',WalletData::getModelTypeByModel($request->model_type));
            }

            if($request->model_id){
                $eloquentData->where('model_id',$request->model_id);
            }

            if($request->type){
                $eloquentData->where('type',$request->type);
            }

            if($request->status){
                $eloquentData->where('status',$request->status);
            }

            if($request->from_type){
                $eloquentData->where('w_from.walletowner_type',WalletData::getModelTypeByModel($request->from_type));
            }

            if($request->from_id){
                $eloquentData->where('w_from.walletowner_id',$request->from_id);
            }

            if($request->to_type){
                $eloquentData->where('w_to.walletowner_type',WalletData::getModelTypeByModel($request->to_type));
            }

            if($request->to_id){
                $eloquentData->where('w_to.walletowner_id',$request->to_id);
            }

            if($request->amount){
                whereBetween($eloquentData,'amount',$request->amount1,$request->amount2);
            }

            $systemLang = $this->systemLang;

            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL->select([DB::raw('SUM(`transactions`.`amount`) as `total`')])->first()->total;

            return Datatables::eloquent($eloquentData)
                ->addColumn('details',function($data){
                    return ' ';
                })
                ->addColumn('id','{{$id}}')
                ->addColumn('model_id',function($data){
                    if(!$data->model){
                        return '--';
                    }else{
                        return WalletData::getModelTypeByModel($data->model_type) . ' ('.$data->model_id.') ';
                    }
                })
                ->addColumn('amount','{{amount($amount)}}')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('type', function($data){
                    return __($data->type);
                })
                ->addColumn('status', function($data){
                    return __($data->status);
                })
                ->addColumn('action',function($data){
                    return "<button class=\"btn btn-primary\" type=\"button\" onclick='urlIframe(\"".route('system.wallet.transactions.show',$data->id)."\")'><i class=\"ft-eye\"></i></button>";
                })
                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                '#',
                __('ID'),
                __('Model'),
                __('Amount'),
                __('Created At'),
                __('Type'),
                __('Status'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Wallets'),
                'url'=> route('system.wallet.index')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Transactions')
            ];

            $this->viewData['pageTitle'] = __('Transactions');

            $this->viewData['walletUserType'] = WalletData::$ownerType;
            $this->viewData['walletModelType'] = WalletData::$modelType+['transfer'=>__('Transfer')];

            return $this->view('wallet.transactions',$this->viewData);
        }
    }

    public function transactionShow($ID){

        $data = WalletTransaction::leftJoin('wallet as w_from','w_from.id','=','transactions.from_id')
            ->leftJoin('wallet as w_to','w_to.id','=','transactions.to_id')
            ->where('transactions.id',$ID)
            ->select(['transactions.*']);

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id())){

            $managed_staff_ids = Auth::user()->managed_staff_ids();
            $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);


            $data->where(function($data) use ($managed_staff_ids, $getMerchantIDsByStaffIDs) {
                $data->where(function($query) use($getMerchantIDsByStaffIDs, $managed_staff_ids){
                    $query->where(function($query) use ($getMerchantIDsByStaffIDs, $managed_staff_ids) {
                        $query->where(function($q1) use ($managed_staff_ids) {
                            $q1->where('w_from.walletowner_type',Auth::user()->modelPath);
                            $q1->whereIn('w_from.walletowner_id',$managed_staff_ids);
                        });

                        if($getMerchantIDsByStaffIDs){
                            $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                                $q2->where('w_from.walletowner_type',(new Merchant)->modelPath);
                                $q2->whereIn('w_from.walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                            });
                        }
                    });
                });

                $data->orWhere(function($query) use($getMerchantIDsByStaffIDs,$managed_staff_ids){
                    $query->where(function($query) use ($getMerchantIDsByStaffIDs,$managed_staff_ids) {
                        $query->where(function($q1) use ($managed_staff_ids) {
                            $q1->where('w_to.walletowner_type',Auth::user()->modelPath);
                            $q1->whereIn('w_to.walletowner_id',$managed_staff_ids);
                        });

                        if($getMerchantIDsByStaffIDs){
                            $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                                $q2->where('w_to.walletowner_type',(new Merchant)->modelPath);
                                $q2->whereIn('w_to.walletowner_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                            });
                        }
                    });
                });
            });


        }



        $data = $data->firstOrFail();

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Wallets'),
            'url'=> route('system.wallet.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transactions'),
            'url'=> route('system.wallet.transactions')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Transaction #ID:').$data->id,
        ];

        $this->viewData['pageTitle'] = __('Transaction #ID:').$data->id;


        $this->viewData['result'] = $data;
        $this->viewData['WalletData'] = WalletData::class;
        return $this->view('wallet.transactionShow',$this->viewData);
    }

}
