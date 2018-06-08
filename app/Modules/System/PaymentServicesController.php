<?php

namespace App\Modules\System;

use App\Models\CommissionList;
use App\Models\PaymentInvoice;
use App\Models\PaymentOutput;
use App\Models\PaymentServiceProviders;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use App\Models\PaymentSDK;
use App\Http\Requests\PaymentServicesFormRequest;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class PaymentServicesController extends SystemController
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

            $eloquentData = PaymentServices::select([
                'payment_services.id',
                'payment_services.payment_sdk_id',
                'payment_sdk.name as payment_sdk_name',
                "payment_service_providers.name_".$this->systemLang." as payment_service_providers_name",
                'payment_services.payment_service_provider_id',
                "payment_services.name_".$this->systemLang." as name",
                "payment_services.description_".$this->systemLang." as description",
                "payment_services.icon",
                "payment_services.status",
                'commission_list.name as commission_list_name',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
            ])
                ->join('payment_sdk','payment_sdk.id','=','payment_services.payment_sdk_id')
                ->leftJoin('commission_list','commission_list.id','=','payment_services.commission_list_id')
                ->join('payment_service_providers','payment_service_providers.id','=','payment_services.payment_service_provider_id')
                ->join('staff','staff.id','=','payment_services.staff_id');




            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'payment_services.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('payment_services.id', '=',$request->id);
            }

            if($request->commission_list_id){
                $eloquentData->where('payment_services.commission_list_id', '=',$request->commission_list_id);
            }

            if($request->payment_sdk_id){
                $eloquentData->where('payment_services.payment_sdk_id', '=',$request->payment_sdk_id);
            }

            if($request->payment_service_provider_id){
                $eloquentData->where('payment_services.payment_service_provider_id', '=',$request->payment_service_provider_id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'payment_services.name',$request->name);
            }

            if($request->status){
                $eloquentData->where('payment_services.status', '=',$request->status);
            }

            if($request->staff_id){
                $eloquentData->where('payment_services.staff_id','=',$request->staff_id);
            }

            if($request->payment_output_id){
                $eloquentData->where('payment_services.payment_output_id','=',$request->payment_output_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->icon,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('payment_sdk_name',function($data){
                    return '<a href="'.route('payment.sdk.show',$data->payment_sdk_id).'" target="_blank">'.$data->payment_sdk_name.'</a>';
                })
                ->addColumn('payment_service_providers_name',function($data){
                    return '<a href="'.route('payment.service-providers.show',$data->payment_service_provider_id).'" target="_blank">'.$data->payment_service_providers_name.'</a>';
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.services.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.services.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.services.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = [__('ID'),__('Icon'),__('Name'),__('Description'),__('SDK'),__('Service Provider'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Service')
            ];
            $this->viewData['tableStatus'] = 7;

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Services');
            }else{
                $this->viewData['pageTitle'] = __('Services');
            }

            $this->viewData['PaymentSDK']              = PaymentSDK::get(['id','name']);
            $this->viewData['PaymentServiceProviders'] = PaymentServiceProviders::get(['id','name_'.$this->systemLang.' as name']);

            $this->viewData['PaymentOutput']  = PaymentOutput::get(['id','name']);
            $this->viewData['CommissionList'] = CommissionList::get(['id','name']);

            return $this->view('payment.services.index',$this->viewData);
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
            'text'=> __('Services'),
            'url'=> route('payment.services.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Payment Services'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment Services');

        // -- View Vars
        $this->viewData['PaymentSDKGroup'] = [];
        $this->viewData['PaymentSDK']      = PaymentSDK::get(['id','name']);
        $this->viewData['PaymentOutput'] = PaymentOutput::get(['id','name']);
        $this->viewData['PaymentServiceProviders'] = PaymentServiceProviders::get(['id','name_'.$this->systemLang.' as name']);
        $this->viewData['CommissionList'] = CommissionList::get(['id','name']);

        // -- View Vars

        return $this->view('payment.services.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentServicesFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('payment-services/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();

        if(PaymentServices::create($theRequest))
            return redirect()
                ->route('payment.services.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.services.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment Services'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentServices $service){

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Services'),
            'url'=> route('payment.services.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $service->{'name_'.$this->systemLang},
        ];

        $service->with(['payment_service_apis','payment_sdk','payment_service_provider']);


        $countPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('COUNT(payment_invoice.id) as `count`')
            ])
            ->where('payment_services.id','=',$service->id)
            ->get();

        $sumPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('SUM(`payment_invoice`.`total`) as `total`'),
                \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
            ])
            ->where('payment_services.id','=',$service->id)
            ->get();

        $this->viewData['countPaymentInvoice'] = array_column($countPaymentInvoice->toArray(),'count','status');
        $this->viewData['sumTotal']            = array_column($sumPaymentInvoice->toArray(),'total','status');
        $this->viewData['sumTotalAmount']      = array_column($sumPaymentInvoice->toArray(),'total_amount','status');
        $this->viewData['result'] = $service;
        $this->viewData['pageTitle'] = $service->{'name_'.$this->systemLang};

        return $this->view('payment.services.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentServices $service)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Services'),
            'url'=> route('payment.services.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment Services'),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment Services');
        $this->viewData['result'] = $service;

        $this->viewData['PaymentSDK']              = PaymentSDK::get(['id','name']);
        $this->viewData['PaymentServiceProviders'] = PaymentServiceProviders::get(['id','name_'.$this->systemLang.' as name']);
        $this->viewData['PaymentOutput'] = PaymentOutput::get(['id','name']);
        $this->viewData['CommissionList'] = CommissionList::get(['id','name']);


        return $this->view('payment.services.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentServicesFormRequest $request,PaymentServices $service)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('payment-services/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['icon']);
        }

        if($service->update($theRequest))
            return redirect()
                ->route('payment.services.edit',$service->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment Services'));
        else{
            return redirect()
                ->route('payment.services.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment Services'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentServices $service,Request $request)
    {
        // Delete Data
        $service->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Services has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.services.index')
                ->with('status','success')
                ->with('msg',__('This Payment Services has been deleted'));
        }
    }



}
