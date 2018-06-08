<?php

namespace App\Modules\System;

use App\Http\Requests\PaymentServiceAPIParameterFormRequest;
use App\Models\PaymentSDK;
use App\Models\PaymentServiceAPIs;
use App\Models\PaymentServiceAPIParameters;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Auth;

class PaymentServiceAPIParametersController extends SystemController
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

            $eloquentData = PaymentServiceAPIParameters::select([
                'payment_service_api_parameters.id',
                'payment_service_api_parameters.payment_services_api_id',
                'payment_service_api_parameters.name_'.$this->systemLang.' as name',
                'payment_service_apis.name as payment_service_api_name',
                'payment_services.id as payment_services_id',
                'payment_services.name_'.$this->systemLang.' as payment_services_name',
                'payment_sdk.name as payment_sdk_name',
                'payment_sdk.id as payment_sdk_id',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
                'staff.id as staff_id'
            ])
                ->join('payment_service_apis','payment_service_apis.id','=','payment_service_api_parameters.payment_services_api_id')
                ->join('payment_services','payment_services.id','=','payment_service_apis.payment_service_id')
                ->join('payment_sdk','payment_sdk.id','=','payment_services.payment_sdk_id')
                ->join('staff','staff.id','=','payment_service_api_parameters.staff_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'payment_service_api_parameters.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('payment_service_api_parameters.id', '=',$request->id);
            }

            if($request->name){
                orWhereByLang($eloquentData,'payment_service_api_parameters.name',$request->name);
            }

            if($request->external_system_id){
                $eloquentData->where('payment_service_api_parameters.external_system_id', '=',$request->external_system_id);
            }

            if($request->payment_services_api_id){
                $eloquentData->where('payment_service_api_parameters.payment_services_api_id', '=',$request->payment_services_api_id);
            }


            if($request->payment_services){
                $eloquentData->where('payment_services.id','=',$request->payment_services);
            }

            if($request->payment_sdk){
                $eloquentData->where('payment_sdk.id','=',$request->payment_sdk);
            }

            if($request->staff_id){
                $eloquentData->where('payment_service_api_parameters.staff_id', '=',$request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('payment_sdk_name',function($data){
                    return '<a href="'.route('payment.sdk.show',$data->payment_sdk_id).'">'.$data->payment_sdk_name.'</a>';
                })
                ->addColumn('payment_services_name',function($data){
                    return '<a href="'.route('payment.services.show',$data->payment_services_id).'">'.$data->payment_services_name.'</a>';
                })
                ->addColumn('payment_service_api_name',function($data){
                    return '<a href="'.route('payment.service-api.show',$data->payment_service_api_id).'">'.$data->payment_service_api_name.'</a>';
                })
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-api-parameters.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.service-api-parameters.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.service-api-parameters.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('SDK'),
                __('Payment Service'),
                __('Payment Service API'),
                __('Created By'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Payment Service API Parameters')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Payment Service API Parameters');
            }else{
                $this->viewData['pageTitle'] = __('Payment Service API Parameters');
            }

            $this->viewData['paymentSDK'] = PaymentSDK::get(['id','name']);
            $this->viewData['paymentServices'] = PaymentServices::get(['id','name_'.$this->systemLang.' as name']);

            return $this->view('payment.service-api-parameters.index',$this->viewData);
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
            'text'=> __('Payment Service API Parameters'),
            'url'=> route('payment.service-api-parameters.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Payment Service API Parameters'),
        ];

        $this->viewData['pageTitle'] = __('Create Payment Service API Parameters');
        $this->viewData['paymentServiceAPIs'] = PaymentServiceAPIs::get(['id','name']);

        return $this->view('payment.service-api-parameters.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentServiceAPIParameterFormRequest $request)
    {
        $theRequest = $request->all();
        $theRequest['staff_id'] = Auth::id();

        if(PaymentServiceAPIParameters::create($theRequest))
            return redirect()
                ->route('payment.service-api-parameters.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.service-api-parameters.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment Service API Parameters'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(){
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentServiceAPIParameters $service_api_parameter)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Payment Service API Parameters'),
            'url'=> route('payment.service-api-parameters.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment Service API Parameter'),
        ];


        $this->viewData['pageTitle'] = __('Edit Payment Service API Parameter');
        $this->viewData['result'] = $service_api_parameter;
        $this->viewData['paymentServiceAPIs'] = PaymentServiceAPIs::get(['id','name']);

        return $this->view('payment.service-api-parameters.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentServiceAPIParameterFormRequest $request,PaymentServiceAPIParameters $service_api_parameter)
    {
        $theRequest = $request->all();

        if($service_api_parameter->update($theRequest))
            return redirect()
                ->route('payment.service-api-parameters.edit',$service_api_parameter->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment Service API Parameters'));
        else{
            return redirect()
                ->route('payment.service-api-parameters.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment Service API Parameters'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentServiceAPIParameters $service_api_parameter,Request $request){
        // Delete Data
        $service_api_parameter->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Service API Parameter has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.service-api-parameters.index')
                ->with('status','success')
                ->with('msg',__('This Payment Service API Parameter has been deleted'));
        }
    }



}
