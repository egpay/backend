<?php

namespace App\Modules\System;

use App\Models\MerchantStaffGroup;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantStaffGroupFormRequest;
use Illuminate\Http\Request;
use Auth;
use App\Models\Merchant;

class MerchantStaffGroupController extends SystemController
{
    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Merchant'),
                'url'=> url('system/merchant')
            ]
        ];
    }

    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = MerchantStaffGroup::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'DATE(merchant_staff_groups.created_at)',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_staff_groups.id', '=',$request->id);
            }

            if($request->merchant_id){
                $eloquentData->where('merchant_staff_groups.merchant_id',$request->merchant_id);
            }

            if($request->title){
                $eloquentData->where('merchant_staff_groups.title','LIKE',"%{$request->title}%");
            }

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('title','{{$title}}')
                ->addColumn('merchant_name',function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant_id).'">'.$data->merchant_name.'</a>';
                })
                ->addColumn('count_staff','{{$merchant_staff_count}}')
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.staff-group.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.staff-group.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.staff-group.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = ['ID','Title','Merchant','Num. Staff','Action'];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Staff Group');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Staff Group');
            }


            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Staff Group'),
            ];

            return $this->view('merchant.staff-group.index',$this->viewData);
        }
    }


    public function create(){
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Staff Group'),
            'url'=> url('system/merchant/staff-group')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Staff Group'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Staff Group');

        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::where('id',$merchantID);
            if(!staffCan('show-tree-users-data',Auth::id())) {
                $merchantData->whereIn('staff_id', Auth::user()->managed_staff_ids());
            }
            $merchantData = $merchantData->firstOrFail();
            $this->viewData['merchantData'] = $merchantData;
        }

        return $this->view('merchant.staff-group.create',$this->viewData);
    }


    public function store(MerchantStaffGroupFormRequest $request)
    {
        if(MerchantStaffGroup::create($request->all()))
            return redirect()
                ->route('merchant.staff-group.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()->route('merchant.staff-group.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Staff Group'));
        }

    }


    public function show(MerchantStaffGroup $staff_group){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_group->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Staff Group'),
            'url'=> url('system/merchant/staff-group')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Staff Group'),
        ];

        $this->viewData['pageTitle'] = __('Merchant Staff Group');
        $this->viewData['result'] = $staff_group;

        return $this->view('merchant.staff-group.show',$this->viewData);

    }


    public function edit(MerchantStaffGroup $staff_group)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_group->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Staff Group'),
            'url'=> url('system/merchant/staff-group')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Staff Group'),
        ];

        $this->viewData['pageTitle'] = __('Edit Merchant Staff Group');

        $this->viewData['result'] = $staff_group;

        return $this->view('merchant.staff-group.create',$this->viewData);
    }


    public function update(MerchantStaffGroupFormRequest $request,MerchantStaffGroup $staff_group)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_group->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        if($staff_group->update($request->all())) {
            return redirect()
                ->route('merchant.staff-group.edit',$staff_group->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Staff Group'));
        }else{
            return redirect()->route('merchant.staff-group.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Staff Group'));
        }
    }


    public function destroy(MerchantStaffGroup $staff_group,Request $request){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff_group->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        // Delete Data
        $staff_group->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant Staff Group has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.staff-group.index')
                ->with('status','success')
                ->with('msg',__('This Merchant Staff Group has been deleted'));
        }
    }
}