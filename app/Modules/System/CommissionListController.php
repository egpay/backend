<?php

namespace App\Modules\System;

use App\Http\Requests\CommissionListFormRequest;
use App\Models\CommissionList;
use Illuminate\Http\Request;
use Auth;
use Yajra\Datatables\Facades\Datatables;

class CommissionListController extends SystemController{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
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

            $eloquentData = CommissionList::select([
                'commission_list.id',
                'commission_list.name',
                'commission_list.description',
                'commission_list.commission_type',
                'commission_list.created_at',
                'commission_list.staff_id',
                'commission_list.condition_data',
                \DB::Raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name")
            ])
                ->join('staff','staff.id','=','commission_list.staff_id');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('description',function($data){
                    if($data->commission_type == 'one'){
                        if($data->condition_data['charge_type'] == 'percent'){
                            $chargeType = 'Percentage';
                        }else{
                            $chargeType = 'Fixed';
                        }

                        return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('Charge Type').'</td>
                                        <td>'.$chargeType.'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('System Commission').'</td>
                                        <td>'.$data->condition_data['system_commission'].'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Agent Commission').'</td>
                                        <td>'.$data->condition_data['agent_commission'].'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Merchant Commission').'</td>
                                        <td>'.$data->condition_data['merchant_commission'].'</td>
                                    </tr>
                                </tbody>
                            </table>';
                    }

                    return __('Edit To View');
                })
                ->addColumn('commission_type','{{ucfirst($commission_type)}}')
                ->addColumn('staff_id','<a target="_blank" href="{{route(\'system.staff.index\',$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.commission-list.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.commission-list.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.commission-list.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{
            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('Details'),
                __('Type'),
                __('Created By'),
                __('Created At'),
                __('Action')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Commission List')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Commission List');
            }else{
                $this->viewData['pageTitle'] = __('Commission List');
            }

            return $this->view('commission-list.index',$this->viewData);
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
            'text'=> __('Commission List'),
            'url'=> route('system.commission-list.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Commission List'),
        ];

        $this->viewData['pageTitle'] = __('Create Commission List');

        return $this->view('commission-list.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommissionListFormRequest $request)
    {

        $theRequest = [];

        $theRequest['name'] = $request->name;
        $theRequest['description'] = $request->description;
        $theRequest['commission_type'] = $request->commission_type;

        if($request->commission_type == 'one'){
            $theRequest['condition_data'] = [
                'charge_type'=> $request->condition_data_charge_type,
                'system_commission'=> $request->condition_data_system_commission,
                'agent_commission'=> $request->condition_data_agent_commission,
                'merchant_commission'=> $request->condition_data_merchant_commission
            ];
        }else{
            $theRequest['condition_data'] = [];
            foreach ($request->condition_data['amount_from'] as $key => $value){
                $theRequest['condition_data'][] = [
                    'amount_from'=> $value,
                    'amount_to'=> $request->condition_data['amount_to'][$key],
                    'charge_type'=> $request->condition_data['charge_type'][$key],
                    'system_commission'=> $request->condition_data['system_commission'][$key],
                    'agent_commission'=> $request->condition_data['agent_commission'][$key],
                    'merchant_commission'=> $request->condition_data['merchant_commission'][$key]
                ];
            }
        }


        $theRequest['staff_id'] = Auth::id();


        if(CommissionList::create($theRequest))
            return redirect()
                ->route('system.commission-list.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.commission-list.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Commission List'));
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
    public function edit(CommissionList $commission_list)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Commission List'),
            'url'=> route('system.commission-list.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Commission List'),
        ];

        $this->viewData['pageTitle'] = __('Edit Commission List');
        $this->viewData['result'] = $commission_list;

        return $this->view('commission-list.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(CommissionListFormRequest $request, CommissionList $commission_list)
    {
        $theRequest = [];

        $theRequest['name'] = $request->name;
        $theRequest['description'] = $request->description;
        $theRequest['commission_type'] = $request->commission_type;

        if($request->commission_type == 'one'){
            $theRequest['condition_data'] = [
                'charge_type'=> $request->condition_data_charge_type,
                'system_commission'=> $request->condition_data_system_commission,
                'agent_commission'=> $request->condition_data_agent_commission,
                'merchant_commission'=> $request->condition_data_merchant_commission
            ];
        }else{
            $theRequest['condition_data'] = [];
            foreach ($request->condition_data['amount_from'] as $key => $value){
                $theRequest['condition_data'][] = [
                    'amount_from'=> $value,
                    'amount_to'=> $request->condition_data['amount_to'][$key],
                    'charge_type'=> $request->condition_data['charge_type'][$key],
                    'system_commission'=> $request->condition_data['system_commission'][$key],
                    'agent_commission'=> $request->condition_data['agent_commission'][$key],
                    'merchant_commission'=> $request->condition_data['merchant_commission'][$key]
                ];
            }
        }

        if($commission_list->update($theRequest))
            return redirect()
                ->route('system.commission-list.edit',$commission_list->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Commission List'));
        else{
            return redirect()
                ->route('system.commission-list.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Commission List'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(CommissionList $commission_list)
    {
        // Delete Data
        $commission_list->delete();
        if($commission_list->ajax()){
            return ['status'=> true,'msg'=> __('Commission List has been deleted successfully')];
        }else{
            redirect()
                ->route('system.commission-list.index')
                ->with('status','success')
                ->with('msg',__('This Commission List has been deleted'));
        }
    }



}
