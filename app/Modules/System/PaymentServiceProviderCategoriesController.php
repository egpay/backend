<?php

namespace App\Modules\System;

use App\Models\PaymentInvoice;
use App\Models\PaymentServiceProviderCategories;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentServiceProviderCategoriesFormRequest;
use Yajra\Datatables\Facades\Datatables;
use Auth;
use DB;

class PaymentServiceProviderCategoriesController extends SystemController
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

            $eloquentData = PaymentServiceProviderCategories::select([
                'payment_service_provider_categories.id',
                DB::raw("CONCAT(payment_service_provider_categories.name_en,' (',payment_service_provider_categories.name_ar,')') as name"),
                "payment_service_provider_categories.description_".$this->systemLang." as description",
                'payment_service_provider_categories.icon',
                'payment_service_provider_categories.status',
                'payment_service_provider_categories.staff_id'
            ]);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            /*
             * Start handling filter
             */

            whereBetween($eloquentData,'payment_service_provider_categories.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('payment_service_provider_categories.id', '=',$request->id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'payment_service_provider_categories.name',$request->name);
            }

            if($request->status){
                $eloquentData->where('payment_services.status', '=',$request->status);
            }

            if($request->staff_id){
                $eloquentData->where('payment_services.staff_id','=',$request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->icon,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-provider-categories.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-provider-categories.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.service-provider-categories.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = [__('ID'),__('Icon'),__('Name'),__('Description'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Service Provider Categories')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Services Provider Categories');
            }else{
                $this->viewData['pageTitle'] = __('Services Provider Categories');
            }

            return $this->view('payment.service-provider-categories.index',$this->viewData);
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
            'url'=> route('payment.service-provider-categories.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Payment Services Provider Categories'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment Services Provider Categories');

        return $this->view('payment.service-provider-categories.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentServiceProviderCategoriesFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('payment-service-provider-categories/'.date('y').'/'.date('m'));
        }
        $theRequest['staff_id'] = Auth::id();

        if(PaymentServiceProviderCategories::create($theRequest))
            return redirect()
                ->route('payment.service-provider-categories.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.service-provider-categories.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment Service Provider Categories'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentServiceProviderCategories $service_provider_category){
        $service_provider_category->with('payment_service_providers');


        $countPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join("payment_service_providers",'payment_service_providers.id','=','payment_services.payment_service_provider_id')
            ->join('payment_service_provider_categories','payment_service_provider_categories.id','=','payment_service_providers.payment_service_provider_category_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('COUNT(payment_invoice.id) as `count`')
            ])
            ->where('payment_service_provider_categories.id','=',$service_provider_category->id)
            ->get();

        $sumPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join("payment_service_providers",'payment_service_providers.id','=','payment_services.payment_service_provider_id')
            ->join('payment_service_provider_categories','payment_service_provider_categories.id','=','payment_service_providers.payment_service_provider_category_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('SUM(`payment_invoice`.`total`) as `total`'),
                \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
            ])
            ->where('payment_service_provider_categories.id','=',$service_provider_category->id)
            ->get();

        $this->viewData['countPaymentInvoice'] = array_column($countPaymentInvoice->toArray(),'count','status');
        $this->viewData['sumTotal']            = array_column($sumPaymentInvoice->toArray(),'total','status');
        $this->viewData['sumTotalAmount']      = array_column($sumPaymentInvoice->toArray(),'total_amount','status');


        $this->viewData['breadcrumb'][] = [
            'text'=> __('Service Provider Categories'),
            'url'=> route('payment.service-provider-categories.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $service_provider_category->{'name_'.$this->systemLang},
        ];

        $this->viewData['result'] = $service_provider_category;
        $this->viewData['pageTitle'] = $service_provider_category->{'name_'.$this->systemLang};

        return $this->view('payment.service-provider-categories.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentServiceProviderCategories $service_provider_category)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Services'),
            'url'=> route('payment.service-provider-categories.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment Service Provider Categories'),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment Service Provider Categories');
        $this->viewData['result'] = $service_provider_category;

        return $this->view('payment.service-provider-categories.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentServiceProviderCategoriesFormRequest $request,PaymentServiceProviderCategories $service_provider_category)
    {
        $theRequest = $request->all();
        if($request->file('icon')) {
            $theRequest['icon'] = $request->icon->store('payment-service-provider-categories/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['icon']);
        }

        if($service_provider_category->update($theRequest))
            return redirect()
                ->route('payment.service-provider-categories.edit',$service_provider_category->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment Service Provider Categories'));
        else{
            return redirect()
                ->route('payment.service-provider-categories.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment Service Provider Categories'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentServiceProviderCategories $service_provider_category,Request $request)
    {
        // Delete Data
        $service_provider_category->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Service Provider Categories has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.service-provider-categories.index')
                ->with('status','success')
                ->with('msg',__('This Payment Service Provider Categories has been deleted'));
        }
    }



}
