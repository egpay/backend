<?php

namespace App\Modules\System;

use App\Http\Requests\PaymentServiceProvidersFormRequest;
use App\Models\PaymentInvoice;
use App\Models\PaymentSDKGroup;
use App\Models\PaymentServiceProviderCategories;
use App\Models\PaymentServiceProviders;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentServicesFormRequest;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class PaymentServiceProvidersController extends SystemController
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

            $eloquentData = PaymentServiceProviders::select([
                'payment_service_providers.id',
                'payment_service_providers.payment_service_provider_category_id',
                "payment_service_providers.name_".$this->systemLang." as name",
                "payment_service_providers.description_".$this->systemLang." as description",
                'payment_service_providers.logo',
                'payment_service_providers.status',
                'payment_service_provider_categories.name_'.$this->systemLang.' as payment_service_provider_categories_name',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
            ])
                ->join('payment_service_provider_categories','payment_service_provider_categories.id','=','payment_service_providers.payment_service_provider_category_id')
                ->leftJoin('staff','staff.id','=','payment_service_providers.staff_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'payment_service_providers.created_at',$request->created_at1,$request->created_at2);

            if($request->payment_service_provider_category_id){
                $eloquentData->where('payment_service_providers.payment_service_provider_category_id','=',$request->payment_service_provider_category_id);
            }

            if($request->id){
                $eloquentData->where('payment_service_providers.id', '=',$request->id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'payment_service_providers.name',$request->name);
            }

            if($request->status){
                $eloquentData->where('payment_service_providers.status', '=',$request->status);
            }

            if($request->staff_id){
                $eloquentData->where('payment_service_providers.staff_id','=',$request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('payment_service_provider_categories_name',function($data){
                    return '<a href="'.route('payment.service-provider-categories.show',$data->payment_service_provider_category_id).'" target="_blank">'.$data->payment_service_provider_categories_name.'</a>';
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-providers.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-providers.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.service-providers.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Logo'),__('Name'),__('Description'),__('Category'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Service Providers')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Service Providers');
            }else{
                $this->viewData['pageTitle'] = __('Service Providers');
            }

            $this->viewData['PaymentServiceProviderCategories'] = PaymentServiceProviderCategories::get(['id','name_'.$this->systemLang.' as name']);

            return $this->view('payment.service-providers.index',$this->viewData);
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
            'text'=> __('Service Providers'),
            'url'=> route('payment.service-providers.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Payment Service Providers'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment Service Providers');

        $this->viewData['PaymentServiceProviderCategories'] = PaymentServiceProviderCategories::get(['id','name_'.$this->systemLang.' as name']);

        return $this->view('payment.service-providers.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentServiceProvidersFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('payment-service-providers/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();

        if(PaymentServiceProviders::create($theRequest))
            return redirect()
                ->route('payment.service-providers.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.service-providers.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment Service Providers'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentServiceProviders $service_provider){

        $service_provider->with(['payment_service_provider_category','payment_services'=>function($query){
            $query->with('payment_sdk');
        },'wallet']);


        $countPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join("payment_service_providers",'payment_service_providers.id','=','payment_services.payment_service_provider_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('COUNT(payment_invoice.id) as `count`')
            ])
            ->where('payment_service_providers.id','=',$service_provider->id)
            ->get();

        $sumPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join("payment_service_providers",'payment_service_providers.id','=','payment_services.payment_service_provider_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('SUM(`payment_invoice`.`total`) as `total`'),
                \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
            ])
            ->where('payment_service_providers.id','=',$service_provider->id)
            ->get();

        $this->viewData['countPaymentInvoice'] = array_column($countPaymentInvoice->toArray(),'count','status');
        $this->viewData['sumTotal']            = array_column($sumPaymentInvoice->toArray(),'total','status');
        $this->viewData['sumTotalAmount']      = array_column($sumPaymentInvoice->toArray(),'total_amount','status');


        $this->viewData['breadcrumb'][] = [
            'text'=> __('Service Providers'),
            'url'=> route('payment.service-providers.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $service_provider->{'name_'.$this->systemLang},
        ];

        $this->viewData['result'] = $service_provider;
        $this->viewData['pageTitle'] = $service_provider->{'name_'.$this->systemLang};

        return $this->view('payment.service-providers.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentServiceProviders $service_provider)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Service Providers'),
            'url'=> route('payment.service-providers.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment Service Providers'),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment Service Providers');
        $this->viewData['result'] = $service_provider;

        $this->viewData['PaymentServiceProviderCategories'] = PaymentServiceProviderCategories::get(['id','name_'.$this->systemLang.' as name']);

        return $this->view('payment.service-providers.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentServiceProvidersFormRequest $request,PaymentServiceProviders $service_provider)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('payment-services/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['icon']);
        }

        if($service_provider->update($theRequest))
            return redirect()
                ->route('payment.service-providers.edit',$service_provider->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment Service Providers'));
        else{
            return redirect()
                ->route('payment.service-providers.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment Service Providers'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentServiceProviders $service_provider, Request $request)
    {
        // Delete Data
        $service_provider->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Services has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.service-providers.index')
                ->with('status','success')
                ->with('msg',__('This Payment Service Providers has been deleted'));
        }
    }



}
