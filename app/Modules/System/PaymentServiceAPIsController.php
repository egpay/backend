<?php

namespace App\Modules\System;

use App\Http\Requests\PaymentServiceAPIsFormRequest;
use App\Models\PaymentInvoice;
use App\Models\PaymentSDK;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Models\PaymentServiceAPIs;
use Auth;

class PaymentServiceAPIsController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Payment')
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

            $eloquentData = PaymentServiceAPIs::select([
                'payment_service_apis.id',
                'payment_service_apis.payment_service_id',
                'payment_service_apis.service_type',
                'payment_service_apis.name',
                'payment_service_apis.description',
                "payment_services.name_{$this->systemLang} as payment_services_name",
                'payment_sdk.name as payment_sdk_name',
                'payment_sdk.id as payment_sdk_id',
                'payment_services.status',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
                'staff.id as staff_id'
            ])
                ->join('staff','staff.id','=','payment_service_apis.staff_id')
                ->join('payment_services','payment_services.id','=','payment_service_apis.payment_service_id')
                ->join('payment_sdk','payment_sdk.id','=','payment_services.payment_sdk_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'payment_service_apis.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('payment_service_apis.id', '=',$request->id);
            }

            if($request->external_system_id){
                $eloquentData->where('payment_service_apis.external_system_id', '=',$request->external_system_id);
            }

            if($request->payment_service_id){
                $eloquentData->where('payment_services.id','=',$request->payment_service_id);
            }

            if($request->payment_sdk_id){
                $eloquentData->where('payment_sdk.id', '=',$request->payment_sdk_id);
            }

            if($request->name){
                $eloquentData->where('payment_service_apis.name', 'LIKE','%'.$request->name.'%');
            }

            if($request->service_type){
                $eloquentData->where('payment_service_apis.service_type','=',$request->service_type);
            }

            if($request->staff_id){
                $eloquentData->where('payment_service_apis.staff_id', '=',$request->staff_id);
            }



            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name',function($data){
                    return $data->name.' ( '.ucfirst($data->service_type).' )';
                })
                ->addColumn('description',function($data){
                    if(!empty($data->description)){
                        return str_limit($data->description,10);
                    }
                    return '--';
                })
                ->addColumn('payment_sdk_name',function($data){
                    return '<a href="'.route('payment.sdk.show',$data->payment_sdk_id).'">'.$data->payment_sdk_name.'</a>';
                })
                ->addColumn('payment_services_name',function($data){
                    return '<a href="'.route('payment.services.show',$data->payment_service_id).'">'.$data->payment_services_name.'</a>';
                })
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-api.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-api.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.service-api.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                    return '';
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('Description'),__('SDK'),__('Payment Services'),__('Staff'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Payment Services APIs')
            ];
            $this->viewData['tableStatus'] = 7;


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Payment Services APIs');
            }else{
                $this->viewData['pageTitle'] = __('Payment Services APIs');
            }

            $this->viewData['paymentSDK'] = PaymentSDK::get(['id','name']);
            $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);

            return $this->view('payment.service-apis.index',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('SDK'),
            'url'=> route('payment.service-api.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Payment Service API'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment Service API');
        $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);

        return $this->view('payment.service-apis.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentServiceAPIsFormRequest $request)
    {
        $theRequest = $request->all();
        $theRequest['staff_id'] = Auth::id();

        if(PaymentServiceAPIs::create($theRequest))
            return redirect()
                ->route('payment.service-api.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.service-api.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment Service APIs'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentServiceAPIs $service_api){


        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Service API'),
            'url'=> route('payment.service-api.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $service_api->name,
        ];

        $this->viewData['result'] = $service_api;
        $this->viewData['pageTitle'] = $service_api->name;


        $countPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join('payment_service_apis','payment_service_apis.payment_service_id','=','payment_services.id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('COUNT(payment_invoice.id) as `count`')
            ])
            ->where('payment_service_apis.id','=',$service_api->id)
            ->get();

        $sumPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join('payment_service_apis','payment_service_apis.payment_service_id','=','payment_services.id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('SUM(`payment_invoice`.`total`) as `total`'),
                \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
            ])
            ->where('payment_service_apis.id','=',$service_api->id)
            ->get();


        $this->viewData['countPaymentInvoice'] = array_column($countPaymentInvoice->toArray(),'count','status');
        $this->viewData['sumTotal']            = array_column($sumPaymentInvoice->toArray(),'total','status');
        $this->viewData['sumTotalAmount']      = array_column($sumPaymentInvoice->toArray(),'total_amount','status');

        return $this->view('payment.service-apis.show',$this->viewData);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentServiceAPIs $service_api)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('SDK'),
            'url'=> route('payment.service-api.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment Service APIs'),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment Service APIs');
        $this->viewData['result'] = $service_api;
        $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);

        return $this->view('payment.service-apis.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentServiceAPIsFormRequest $request,PaymentServiceAPIs $service_api)
    {
        $theRequest = $request->all();
        if($service_api->update($theRequest))
            return redirect()
                ->route('payment.service-api.edit',$service_api->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment Service APIs'));
        else{
            return redirect()
                ->route('payment.service-api.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment Service APIs'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentServiceAPIs $service_api,Request $request){
        // Delete Data
        $service_api->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Service APIs has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.service-api.index')
                ->with('status','success')
                ->with('msg',__('This Payment Service API has been deleted'));
        }
    }



}
