<?php

namespace App\Modules\System;

use App\Models\LoyaltyProgramIgnore;
use App\Models\LoyaltyPrograms;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\LoyaltyProgramsFormRequest;
use Auth;


class LoyaltyProgramsController extends SystemController{

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

            $eloquentData = LoyaltyPrograms::select([
                'loyalty_programs.id',
                "loyalty_programs.name_".$this->systemLang.' as name',
                "loyalty_programs.description_".$this->systemLang.' as description',
                "loyalty_programs.type",
                "loyalty_programs.status",
                "loyalty_programs.pay_type",
                "loyalty_programs.owner",
                "loyalty_programs.created_at",
                "loyalty_programs.staff_id",
                \DB::raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
            ])
                ->join('staff','staff.id','=','loyalty_programs.staff_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('description','<code>{{str_limit($description,10)}}</code>')
                ->addColumn('type','{{ucfirst(__($type))}}')
                ->addColumn('pay_type','{{ucfirst(__($pay_type))}}')
                ->addColumn('owner','{{ucfirst(__($owner))}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('system.loyalty-programs.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('system.loyalty-programs.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.loyalty-programs.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Name'),
                __('Description'),
                __('Type'),
                __('Pay Type'),
                __('Owner'),
                __('Action')
            ];
            $this->viewData['breadcrumb'][] = [
                'text'=> __('Loyalty Programs')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Loyalty Programs');
            }else{
                $this->viewData['pageTitle'] = __('Loyalty Programs');
            }

            return $this->view('loyalty-programs.index',$this->viewData);
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
            'text'=> __('Loyalty Programs'),
            'url'=> route('system.loyalty-programs.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Loyalty Program'),
        ];


        $this->viewData['pageTitle'] = __('Create Loyalty Program');
        return $this->view('loyalty-programs.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LoyaltyProgramsFormRequest $request)
    {

        $newRequestData = [
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'description_ar'=> $request->description_ar,
            'description_en'=> $request->description_en,
            'type'          => $request->type,
            'transaction_type' => $request->transaction_type,
            'pay_type'      => $request->pay_type,
            'owner'         => $request->owner,
            'staff_id'      => Auth::id()
        ];


        /*[
            'type' => static|dynamic
            // If Static
            // هتاخد 1 بوينت لك 5 جنية
            'list' => [
                'point'=> 1,
                'amount'=> 5
            ]


            // If Dynamic
            // هياخد 1 بوينت لو مصروفاتة بين 10 او 20 جنية
            'list' => [
                [
                    'from_amount' => 10,
                    'to_amount'   => 20,
                    'point'=> 1,
                ]
            ]
        ]*/
        if($request->list_type == 'static'){

            $list = [
                'type'=> 'static',
                'list'=> [
                    'point'  => $request->list_point,
                    'amount' => $request->list_amount
                ]
            ];

        }else{

            $list = [
                'type'=> 'dynamic',
                'list'=> []
            ];

            foreach ($request->list['from_amount'] as $key => $value){
                $list['list'][] = [
                    'from_amount'   => $value,
                    'to_amount'     => $request->list['to_amount'][$key],
                    'point'         => $request->list['point'][$key]
                ];
            }

        }


        $newRequestData['list'] = $list;

        if(LoyaltyPrograms::create($newRequestData))
            return redirect()
                ->route('system.loyalty-programs.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.loyalty-programs.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Loyalty Programs'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(LoyaltyPrograms $loyaltyProgram,Request $request){

        if($request->isDataTable){

            $eloquentData = LoyaltyProgramIgnore::select([
                'loyalty_program_ignore.id',
                "loyalty_program_ignore.description_".$this->systemLang.' as description',

                "loyalty_program_ignore.ignoremodel_id",
                "loyalty_program_ignore.ignoremodel_type",

                "loyalty_program_ignore.staff_id",
                "loyalty_program_ignore.created_at",
                \DB::raw("CONCAT(staff.firstname,' ',staff.lastname) as staff_name"),
            ])
                ->join('staff','staff.id','=','loyalty_program_ignore.staff_id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('ignoremodel_type','{{$ignoremodel_type}} - {{$ignoremodel_id}}')
                ->addColumn('description','<code>{{$description}}</code>')

                ->addColumn('staff_name','<a href="{{route("system.staff.show",$staff_id)}}">{{$staff_name}}</a>')
                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href='javascript:void(0);' onclick=\"urlIframe('".route('system.loyalty-program-ignore.edit',$data->id)."')\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('system.loyalty-program-ignore.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Loyalty Programs'),
            'url'=> route('system.loyalty-programs.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> $loyaltyProgram->{'name_'.$this->systemLang},
        ];

        $this->viewData['result']    = $loyaltyProgram;
        $this->viewData['pageTitle'] = $loyaltyProgram->{'name_'.$this->systemLang};

        return $this->view('loyalty-programs.show',$this->viewData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(LoyaltyPrograms $loyaltyProgram)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Loyalty Programs'),
            'url'=> route('system.loyalty-programs.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Loyalty Programs'),
        ];

        $this->viewData['pageTitle'] = __('Edit Loyalty Programs');
        $this->viewData['result'] = $loyaltyProgram;

        return $this->view('loyalty-programs.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(LoyaltyProgramsFormRequest $request,LoyaltyPrograms $loyaltyProgram)
    {

        $newRequestData = [
            'name_ar'       => $request->name_ar,
            'name_en'       => $request->name_en,
            'description_ar'=> $request->description_ar,
            'description_en'=> $request->description_en,
            'type'          => $request->type,
            'transaction_type' => $request->transaction_type,
            'pay_type'      => $request->pay_type,
            'owner'         => $request->owner
        ];


        /*[
            'type' => static|dynamic
            // If Static
            // هتاخد 1 بوينت لك 5 جنية
            'list' => [
                'point'=> 1,
                'amount'=> 5
            ]


            // If Dynamic
            // هياخد 1 بوينت لو مصروفاتة بين 10 او 20 جنية
            'list' => [
                [
                    'from_amount' => 10,
                    'to_amount'   => 20,
                    'point'=> 1,
                ]
            ]
        ]*/
        if($request->list_type == 'static'){

            $list = [
                'type'=> 'static',
                'list'=> [
                    'point'  => $request->list_point,
                    'amount' => $request->list_amount
                ]
            ];

        }else{

            $list = [
                'type'=> 'dynamic',
                'list'=> []
            ];

            foreach ($request->list['from_amount'] as $key => $value){
                $list['list'][] = [
                    'from_amount'   => $value,
                    'to_amount'     => $request->list['to_amount'][$key],
                    'point'         => $request->list['point'][$key]
                ];
            }

        }


        $newRequestData['list'] = $list;

        if($loyaltyProgram->update($newRequestData))
            return redirect()
                ->route('system.loyalty-programs.edit',$loyaltyProgram->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Loyalty Programs'));
        else{
            return redirect()
                ->route('system.loyalty-programs.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Loyalty Programs'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(LoyaltyPrograms $loyaltyProgram,Request $request)
    {
        // Delete Data
        $loyaltyProgram->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Payment Output has been deleted successfully')];
        }else{
            redirect()
                ->route('system.loyalty-programs.index')
                ->with('status','success')
                ->with('msg',__('This Loyalty Programs has been deleted'));
        }
    }

}