<?php

namespace App\Modules\System;

use App\Libs\Payments\Payments;
use App\Libs\WalletData;
use App\Models\Merchant;
use App\Models\PaymentSDK;
use App\Models\PaymentServiceAPIParameters;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Models\PaymentTransactions;
use Auth;

class PaymentTransactionsController extends SystemController
{

    public function __construct(Request $request){
        parent::__construct();

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }

    public function index(Request $request){
        // @TODO: عايز اضيف في payment_transactions حقل لو الركوست تم بناجاح او لا وفي الفلتر كمان
        if($request->isDataTable){

            $eloquentData = PaymentTransactions::with(['model','payment_services'])
                ->select(
                    [
                        'payment_transactions.id',
                        'payment_transactions.model_id',
                        'payment_transactions.model_type',
                        'payment_transactions.service_type',
                        'payment_transactions.external_system_id',
                        'payment_transactions.payment_services_id',
                        'payment_transactions.amount',
                        'payment_transactions.total_amount',
                        'payment_transactions.response',
                        'payment_transactions.created_at',
                        'payment_transactions.response_type',

                        'payment_services.name_'.$this->systemLang.' as payment_services_name',
                        'payment_services.icon as payment_services_icon',

                        'payment_service_providers.name_'.$this->systemLang.' as payment_service_providers_name',
                        'payment_service_providers.logo as payment_service_providers_logo',

                        'payment_service_provider_categories.name_'.$this->systemLang.' as payment_service_provider_categories_name'

                    ]
                )
                ->join('payment_services','payment_services.id','=','payment_transactions.payment_services_id')
                ->join('payment_sdk','payment_sdk.id','=','payment_services.payment_sdk_id')
                ->join('payment_service_providers','payment_service_providers.id','=','payment_services.payment_service_provider_id')
                ->join('payment_service_provider_categories','payment_service_provider_categories.id','=','payment_service_providers.payment_service_provider_category_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'payment_transactions.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('payment_transactions.id', '=',$request->id);
            }

            if($request->service_type){
                $eloquentData->where('payment_transactions.service_type','=',$request->service_type);
            }
            
            if($request->external_system_id){
                $eloquentData->where('payment_transactions.external_system_id','=',$request->external_system_id);
            }

            if($request->payment_sdk_id){
                $eloquentData->where('payment_sdk.id','=',$request->payment_sdk_id);
            }

            if($request->payment_services_id){
                $eloquentData->where('payment_transactions.payment_services_id','=',$request->payment_services_id);
            }

            if($request->response_type){
                $eloquentData->where('payment_transactions.response_type','=',$request->response_type);
            }

            whereBetween($eloquentData,'payment_transactions.amount',$request->amount1,$request->amount2);

            whereBetween($eloquentData,'payment_transactions.total_amount',$request->total_amount1,$request->total_amount2);


            if($request->model_type){
                $eloquentData->where('payment_transactions.model_type','=',$request->model_type);
            }

            if($request->model_id){
                $eloquentData->where('payment_transactions.model_id','=',$request->model_id);
            }


            $SYSTEM_TOTAL = clone $eloquentData;
            $SYSTEM_TOTAL = $SYSTEM_TOTAL
                ->select([DB::raw('SUM(`payment_transactions`.`amount`) as `system_total`')])
                ->first()
                ->system_total;

            $SYSTEM_TOTAL_AMOUNT = clone $eloquentData;
            $SYSTEM_TOTAL_AMOUNT = $SYSTEM_TOTAL_AMOUNT
                ->select([DB::raw('SUM(`payment_transactions`.`total_amount`) as `system_total`')])
                ->first()
                ->system_total;

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){

                $managed_staff_ids = Auth::user()->managed_staff_ids();

                $eloquentData->where(function($query) use($managed_staff_ids){

                    $query->where(function($q1) use ($managed_staff_ids) {
                        $q1->where('payment_transactions.model_type',Auth::user()->modelPath);
                        $q1->whereIn('payment_transactions.model_id',$managed_staff_ids);
                    });

                    $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                    if($getMerchantIDsByStaffIDs){
                        $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                            $q2->where('payment_transactions.model_type',(new Merchant)->modelPath);
                            $q2->whereIn('payment_transactions.model_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                        });
                    }

                });

            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('details',function($data){
                    return ' ';
                })
                ->addColumn('response',function($data){
                    if($data->response_type == 'done'){
                        return '<i style="color: green;" class="fa fa-check" aria-hidden="true"></i>';
                    }elseif($data->response_type == 'fail'){
                        return '<i style="color: red;" class="fa fa-times" aria-hidden="true"></i>';
                    }else{
                        return '<i class="fa fa-clock-o" aria-hidden="true"></i>';
                    }
                })
                ->addColumn('id','{{$id}}')
                ->addColumn('model_type',function($data){
                    return adminDefineUser($data->model_type,$data->model_id,$data->model->firstname.' '.$data->model->lastname);
                })
                ->addColumn('service_type','{{__(ucfirst($service_type))}}')
                ->addColumn('payment_services_id',function($data){
                    return '<a href="'.route('payment.services.show',$data->payment_services_id).'">'.$data->payment_services_name.'</a>';
                })
                ->addColumn('amount','{{amount($amount)}}')
                ->addColumn('total_amount','{{amount($total_amount)}}')

                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })

                ->addColumn('SYSTEM-TOTAL',function($data) use ($SYSTEM_TOTAL) {
                    return amount($SYSTEM_TOTAL,true);
                })
                ->addColumn('SYSTEM-TOTAL-AMOUNT',function($data) use ($SYSTEM_TOTAL_AMOUNT) {
                    return amount($SYSTEM_TOTAL_AMOUNT,true);
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('#'),
                __('Status'),
                __('ID'),
                __('User'),
                __('Service Type'),
                __('Payment Service'),
                __('Amount'),
                __('Total Amount'),
                __('Time')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Payment Transactions')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Payment Transactions');
            }else{
                $this->viewData['pageTitle'] = __('Payment Transactions');
            }

            $this->viewData['paymentSDK'] = PaymentSDK::get(['id','name']);
            $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);

            return $this->view('payment.transactions.index',$this->viewData);
        }
    }

    public function ajaxDetails($ID = null){
        $eloquentData = PaymentTransactions::where('id',$ID);


        if(!staffCan('show-tree-users-data',Auth::id())){
            $managed_staff_ids = Auth::user()->managed_staff_ids();

            $eloquentData->where(function($query) use($managed_staff_ids){

                $query->where(function($q1) use ($managed_staff_ids) {
                    $q1->where('payment_transactions.model_type',Auth::user()->modelPath);
                    $q1->whereIn('payment_transactions.model_id',$managed_staff_ids);
                });

                $getMerchantIDsByStaffIDs = Merchant::whereIn('staff_id',$managed_staff_ids)->get(['id']);

                if($getMerchantIDsByStaffIDs){
                    $query->orWhere(function($q2) use ($getMerchantIDsByStaffIDs) {
                        $q2->where('payment_transactions.model_type',(new Merchant)->modelPath);
                        $q2->whereIn('payment_transactions.model_id',array_column($getMerchantIDsByStaffIDs->toArray(),'id'));
                    });
                }

            });

        }


        $data = $eloquentData->firstOrFail();

        $parameter = $data->request_map;
        if($parameter){
            $newParameter = [];
            foreach ($parameter as $key => $value) {
                $newParameter[substr($key,10)] = $value;
            }
            $parameter = $newParameter;
        }else{
            $parameter = [];
        }


        $pKeys = array_keys($parameter);
        $parameterData = PaymentServiceAPIParameters::whereIn('external_system_id',$pKeys)
            ->get(['external_system_id','name_'.$this->systemLang.' as name'])->toArray();


        return ['parameter'=> $parameter,'parameterData'=> is_array($parameterData) ? array_column($parameterData,'name','external_system_id') : [], 'response'=> $data->response ];
    }

}
