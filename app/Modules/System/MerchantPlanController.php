<?php

namespace App\Modules\System;

use App\Models\Merchant;
use App\Models\MerchantContract;
use App\Models\MerchantPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantPlanFormRequest;

class MerchantPlanController extends SystemController
{


    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Merchant')
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
            $systemLang = $this->systemLang;
            $eloquentData = MerchantPlan::select([
                'id',
                'title',
                'description',
                'months',
                'amount',
                'type',
                'created_at'
            ])
            ->addSelect(DB::raw('
                (SELECT COUNT(*) FROM `merchants` 
                INNER JOIN `merchant_contracts` ON `merchants`.`merchant_contract_id` = `merchant_contracts`.`id`
                WHERE `merchant_contracts`.`plan_id` = `merchant_plans`.`id`) as `num_merchants`
            '));


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'merchant_plans.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_plans.id', '=',$request->id);
            }

            if($request->title){
                $eloquentData->where('merchant_plans.title','LIKE',"%{$request->title}%");
            }

            if($request->description){
                $eloquentData->where('merchant_plans.description','LIKE',"%{$request->description}%");
            }

            whereBetween($eloquentData,'merchant_plans.months',$request->months1,$request->months2);
            whereBetween($eloquentData,'merchant_plans.amount',$request->amount1,$request->amount2);


            if($request->staff_id){
                $eloquentData->where('merchant_plans.staff_id',$request->staff_id);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('title','{{$title}}')
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('amount',function($data){
                    return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('Amount').'</td>
                                        <td>'.amount($data->amount).'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Months').'</td>
                                        <td>'.$data->months.'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Num. Merchants').'</td>
                                        <td>'.$data->num_merchants.'</td>
                                    </tr>
                                </tbody>
                            </table>';
                })
                ->addColumn('type',function($data){
                    if(empty($data->type)){
                        return '--';
                    }else{
                        $return = '<table class="table"><tbody>';

                        foreach ($data->type as $value){
                            $return .= '<tr>
                                            <td>'.ucfirst(__($value)).'</td>
                                        </tr>';
                        }

                        $return .= '</tbody></table>';
                        return $return;
                    }


                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.plan.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.plan.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.plan.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Title'),
                __('Description'),
                __('Data'),
                __('Service Type'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Plans'),
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Plans');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Plan');
            }


            return $this->view('merchant.plan.index',$this->viewData);
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
            'text'=> __('Merchant Plans'),
            'url'=> route('merchant.plan.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Plan'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Plan');

        return $this->view('merchant.plan.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantPlanFormRequest $request)
    {

        $theRequest = $request->all();

        $theRequest['staff_id'] = Auth::id();

        if(isset($theRequest['type'])){
            $theRequest['type'] = serialize($theRequest['type']);
        }else{
            $theRequest['type'] = null;
        }

        if(MerchantPlan::create($theRequest))
            return redirect()->route('merchant.plan.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()->route('merchant.plan.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Plan'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantPlan $plan,Request $request){

        $MerchantPlan = $plan;

        if($request->isMerchant){

            $eloquentData = Merchant::viewData($this->systemLang)
                ->where('merchant_contracts.plan_id',$MerchantPlan->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','<a target="_blank" href="{{route(\'merchant.merchant.show\',$id)}}">{{$id}}</a>')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img style="width:70px;height:70px;" src="'.asset('storage/app/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->category_name.') ';
                })
                ->addColumn('staff_firstname',function($data){
                   return '<a target="_blank" href="'.route('system.staff.show',$data->staff_id).'">'.$data->staff_firstname.' '.$data->staff_lastname.'</a>';
                })
                ->make(true);
        }elseif($request->isContract){
            $eloquentData = MerchantContract::viewData($this->systemLang)
                ->where('plan_id',$MerchantPlan->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','<a target="_blank" href="{{route(\'merchant.contract.show\',$id)}}">{{$id}}</a>')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->category_name.') ';
                })
                ->addColumn('price','{{$price}} {{__(\'LE\')}}')
                ->addColumn('start_date','{{$start_date}}')
                ->addColumn('end_date','{{$end_date}}')
                ->addColumn('staff_firstname',function($data){
                    return '<a target="_blank" href="'.url('system/staff/'.$data->staff_id).'">'.$data->staff_firstname.' '.$data->staff_lastname.'</a>';
                })
                ->make(true);
        }else{
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Plans'),
                'url'=> route('merchant.plan.index')
            ];

            $this->viewData['breadcrumb'][] = [
                'text'=> $MerchantPlan->title,
            ];

            $this->viewData['pageTitle'] = __('Merchant Plan');

            $this->viewData['result'] = $MerchantPlan;

            return $this->view('merchant.plan.show',$this->viewData);
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function edit(MerchantPlan $plan)
    {
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Plan'),
            'url'=> route('merchant.plan.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Plan'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Plan');

        $this->viewData['result'] = $plan;
        return $this->view('merchant.plan.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function update(MerchantPlanFormRequest $request,MerchantPlan $plan)
    {
        $theRequest = $request->all();

        if(isset($theRequest['type']))
            $theRequest['type'] = serialize($theRequest['type']);
        else
            $theRequest['type'] = '';

        if($plan->update($theRequest)) {
            return redirect()->route('merchant.plan.edit',$plan->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Plan'));
        }else{
            return redirect()->route('merchant.plan.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Plan'));;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantPlan $plan,Request $request){
        // Delete Data
        $plan->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Plan has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.plan.index')
                ->with('status','success')
                ->with('msg',__('This Plan has been deleted'));
        }
    }




}
