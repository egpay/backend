<?php

namespace App\Modules\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Models\CallTracking;
use Auth;

/**
 * Class CallTrackingController
 * @package App\Modules\System
 */
class CallTrackingController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Coupons'),
                'url'=> url('system/call-tracking')
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

            $eloquentData = CallTracking::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'call_tracking.created_at',$request->created_at1,$request->created_at2);

            if($request->type){
                $eloquentData->where('call_tracking.type', '=',$request->type);
            }

            if($request->id){
                $eloquentData->where('call_tracking.id', '=',$request->id);
            }

            if($request->phone_number){
                $eloquentData->where('call_tracking.phone_number','=',$request->phone_number);
            }

            if($request->caller_name){
                $eloquentData->where('call_tracking.phone_number','=',$request->caller_name);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('type', function($data){
                    if($data->type == 'in'){
                        return '<span class="text-danger">'.__('Call-in').'</span>';
                    } else {
                        return '<span class="text-success">'.__('Call-out').'</span>';
                    }
                })
                ->addColumn('phone_number', function($data){
                    return $data->phone_number;
                })
                ->addColumn('calltime', function($data){
                    return $data->calltime;
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.call-tracking.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.call-tracking.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.call-tracking.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = ['ID',__('Call type'),__('Phone#'),__('Calltime'),__('Action')];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Call record');
            }else{
                $this->viewData['pageTitle'] = __('System Call tracking');
            }

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Call tracking'),
            ];


            return $this->view('call-tracking.index',$this->viewData);
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
            'text'=> __('Merchant Coupon'),
            'url'=> url('system/call-tracking')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Call track'),
        ];

        $this->viewData['pageTitle'] = __('Create Call track');

        return $this->view('call-tracking.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $RequestedData = $request->only(['type','phone_number','calltime','caller_name','details']);
        $this->validate($request,[
            'type'              =>      'required|in:in,out',
            'phone_number'      =>      'required|min:8|max:16',
            'calltime'          =>      'required',
            'caller_name'       =>      'required',
            'details'           =>      'required',
        ]);

        $RequestedData['staff_id'] = Auth::id();

        if(CallTracking::create($RequestedData)){
            return redirect()
                ->route('system.call-tracking.create')
                ->with('status', 'success')
                ->with('msg', __('Call track has been added successfully'));
        } else {
            return redirect()
                ->route('system.call-tracking.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add call track'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(CallTracking $callTracking){
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Merchants'),
                'url'=> route('system.call-tracking.index'),
            ],
            [
                'text'=>  $callTracking->id,
            ]
        ];

        $this->viewData['pageTitle'] = $callTracking->phone_number;
        $this->viewData['result'] = $callTracking;

        return $this->view('call-tracking.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(CallTracking $callTracking){

        $this->viewData['breadcrumb'][] = [
            'text'=> __('system Call track'),
            'url'=> url('system/call-tracking')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit system call track'),
        ];
        $this->viewData['pageTitle'] = __('Edit call track');

        $this->viewData['result'] = $callTracking;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('call-tracking.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,CallTracking $callTracking)
    {
        $RequestedData = $request->only(['type','phone_number','calltime','caller_name','details']);
        $this->validate($request,[
            'type'              =>      'required|in:in,out',
            'phone_number'      =>      'required|min:8|max:16',
            'calltime'          =>      'required',
            'caller_name'       =>      'required',
            'details'           =>      'required',
        ]);

        $RequestedData['staff_id'] = Auth::id();

        if($callTracking->update($RequestedData)) {
            return redirect()->route('system.call-tracking.edit',$callTracking->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited call tracking'));
        }else{
            return redirect()->route('system.call-tracking.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit call tracking'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallTracking $callTracking,Request $request){


        // Delete Data
        if($callTracking->delete()){
            if($request->ajax()){
                return ['status'=> true,'msg'=> __('call track has been deleted successfully')];
            }else{
                redirect()
                    ->route('system.call-tracking.index')
                    ->with('status','success')
                    ->with('msg',__('call track has been deleted successfully'));
            }
        } else {
            if($request->ajax()){
                return ['status'=> false,'msg'=> __('Couldn\'t delete the call track')];
            }else{
                redirect()
                    ->route('system.call-tracking.index')
                    ->with('status','danger')
                    ->with('msg',__('Couldn\'t delete the call track'));
            }
        }

    }


}
