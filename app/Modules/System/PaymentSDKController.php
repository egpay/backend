<?php

namespace App\Modules\System;

use App\Libs\Payments\Adapters\Bee;
use App\Models\PaymentInvoice;
use App\Models\PaymentSDK;
use Illuminate\Http\Request;
use App\Http\Requests\PaymentSDKFormRequest;
use Yajra\Datatables\Facades\Datatables;
use App\Models\AreaType;
use Auth;
use Debugbar;
class PaymentSDKController extends SystemController
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

            $eloquentData = PaymentSDK::select([
                'payment_sdk.id',
                "payment_sdk.name",
                "payment_sdk.adapter_name",
                "payment_sdk.description",
                "payment_sdk.logo",
                "payment_sdk.staff_id",
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
                \DB::Raw("(SELECT COUNT(*) FROM `payment_services` WHERE `payment_services`.`payment_sdk_id` = `payment_sdk`.`id`) as `count`")
            ])
                ->join('staff','staff.id','=','payment_sdk.staff_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->image) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('adapter_name','<code>{{$adapter_name}}</code>')
                ->addColumn('name','{{$name}}')
                ->addColumn('description',function($data){
                    if($data->id == 1){
                        return amount((string) Bee::balance()->data->balance,true);
                    }else{
                        return ' -- ';
                    }
                })
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.sdk.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.sdk.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.sdk.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Logo'),__('Adapter'),__('Name'),__('Balance'),__('Staff'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('SDK')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted SDK');
            }else{
                $this->viewData['pageTitle'] = __('SDK');
            }

            return $this->view('payment.sdk.index',$this->viewData);
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
            'url'=> route('payment.sdk.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Payment SDK'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment SDK');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);

        return $this->view('payment.sdk.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentSDKFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('payment-sdk/'.date('y').'/'.date('m'));
        }

        $theRequest['staff_id'] = Auth::id();
        $theRequest['area_id'] = getLastNotEmptyItem($request->area_id);

        if(PaymentSDK::create($theRequest))
            return redirect()
                ->route('payment.sdk.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.sdk.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment SDK'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentSDK $sdk){


        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('SDK'),
            'url'=> route('payment.sdk.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $sdk->name,
        ];

        $this->viewData['result'] = $sdk;
        $this->viewData['pageTitle'] = $sdk->name;


        $countPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join('payment_sdk','payment_sdk.id','=','payment_services.payment_sdk_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('COUNT(payment_invoice.id) as `count`')
            ])
            ->where('payment_sdk.id','=',$sdk->id)
            ->get();

        $sumPaymentInvoice = PaymentInvoice::join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join("payment_services",'payment_services.id','=','payment_transactions.payment_services_id')
            ->join('payment_sdk','payment_sdk.id','=','payment_services.payment_sdk_id')
            ->groupBy('payment_invoice.status')
            ->select([
                'payment_invoice.status',
                \DB::raw('SUM(`payment_invoice`.`total`) as `total`'),
                \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
            ])
            ->where('payment_sdk.id','=',$sdk->id)
            ->get();


        $this->viewData['countPaymentInvoice'] = array_column($countPaymentInvoice->toArray(),'count','status');
        $this->viewData['sumTotal']            = array_column($sumPaymentInvoice->toArray(),'total','status');
        $this->viewData['sumTotalAmount']      = array_column($sumPaymentInvoice->toArray(),'total_amount','status');

        return $this->view('payment.sdk.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentSDK $sdk)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('SDK'),
            'url'=> route('payment.sdk.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment SDK'),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment SDK');
        $this->viewData['result'] = $sdk;
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);


        Debugbar::info($sdk);


        return $this->view('payment.sdk.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentSDKFormRequest $request,PaymentSDK $sdk)
    {
        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('payment-sdk/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['logo']);
        }
        $theRequest['area_id'] = getLastNotEmptyItem($request->area_id);

        if($sdk->update($theRequest))
            return redirect()
                ->route('payment.sdk.edit',$sdk->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment SDK'));
        else{
            return redirect()
                ->route('payment.sdk.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment SDK'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentSDK $sdk)
    {
        // Delete Data
        $sdk->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment SDK has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.sdk.index')
                ->with('status','success')
                ->with('msg',__('This Payment SDK has been deleted'));
        }
    }



}
