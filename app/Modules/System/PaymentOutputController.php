<?php

namespace App\Modules\System;

use App\Models\PaymentOutput;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\PaymentOutputFormRequest;
use Auth;


class PaymentOutputController extends SystemController
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

            $eloquentData = PaymentOutput::select([
                'payment_output.id',
                "payment_output.name",
                "payment_output.created_at",
                "payment_output.staff_id",
                \DB::raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
                \DB::raw("(SELECT COUNT(*) FROM `payment_services` WHERE `payment_services`.`payment_output_id` = payment_output.id) as `payment_services_count`")
            ])
                ->join('staff','staff.id','=','payment_output.staff_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('payment_services_count','<a target="_blank" href="{{route(\'payment.services.index\',[\'payment_output_id\'=>$id])}}">{{$payment_services_count}}</a>')
                ->addColumn('staff_name','<a href="{{route(\'system.staff.show\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('payment.output.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('payment.output.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('payment.output.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('Num. Services'),__('Created By'),__('Action')];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Services Output')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Service Output');
            }else{
                $this->viewData['pageTitle'] = __('Services Output');
            }

            return $this->view('payment.output.index',$this->viewData);
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
            'text'=> __('Services Output'),
            'url'=> route('payment.output.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Services Output'),
        ];


        $this->viewData['pageTitle'] = __('Create Services Output');
        return $this->view('payment.output.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentOutputFormRequest $request)
    {

        $newRequestData = [
            'name'=> $request->name
        ];

        $parameters = [];
        foreach ($request->key as $key => $value){
            $parameters[] = [
                'key'=> $value,
                'language'=> [
                    'ar'=> $request->language['ar'][$key],
                    'en'=> $request->language['en'][$key],
                ]
            ];
        }

        $newRequestData['parameters'] = $parameters;
        $newRequestData['staff_id'] = Auth::id();

        if(PaymentOutput::create($newRequestData))
            return redirect()
                ->route('payment.output.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('payment.output.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Payment Output'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(PaymentOutput $output){


        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Payment Output'),
            'url'=> route('payment.output.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $output->name,
        ];

        $this->viewData['result'] = $output;
        $this->viewData['pageTitle'] = $output->name;

        return $this->view('payment.output.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentOutput $output)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Payment Output'),
            'url'=> route('payment.output.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Payment Output'),
        ];

        $this->viewData['pageTitle'] = __('Edit Payment Output');
        $this->viewData['result'] = $output;

        return $this->view('payment.output.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentOutputFormRequest $request,PaymentOutput $output)
    {
        $newRequestData = [
            'name'=> $request->name
        ];

        $parameters = [];
        foreach ($request->key as $key => $value){
            $parameters[] = [
                'key'=> $value,
                'language'=> [
                    'ar'=> $request->language['ar'][$key],
                    'en'=> $request->language['en'][$key],
                ]
            ];
        }

        $newRequestData['parameters'] = $parameters;

        if($output->update($newRequestData))
            return redirect()
                ->route('payment.output.edit',$output->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Payment Output'));
        else{
            return redirect()
                ->route('payment.output.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Payment Output'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentOutput $output,Request $request)
    {
        // Delete Data
        $output->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Output has been deleted successfully')];
        }else{
            redirect()
                ->route('payment.output.index')
                ->with('status','success')
                ->with('msg',__('This Payment SDK has been deleted'));
        }
    }

}
