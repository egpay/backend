<?php

namespace App\Modules\System;

use App\Models\MerchantBranch;
use App\Models\MerchantStaff;
use App\Models\MerchantStaffGroup;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\MerchantStaffFormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Merchant;
use Auth;

class MerchantstaffController extends SystemController
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

            $eloquentData = MerchantStaff::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'merchant_staff.created_at',$request->created_at1,$request->created_at2);
            if($request->id){
                $eloquentData->where('merchant_staff.id', '=',$request->id);
            }


            if($request->merchant_id){
                $eloquentData->where('merchant_staff_groups.merchant_id',$request->merchant_id);
            }

            if($request->name){
                $eloquentData->whereRaw('CONCAT(merchant_staff.firstname," ",merchant_staff.lastname) LIKE ("%?%")',[$request->name]);
            }

            if($request->email){
                $eloquentData->where('merchant_staff.email','LIKE','%'.$request->email.'%');
            }

            if($request->mobile){
                $eloquentData->where('merchant_staff.mobile','LIKE','%'.$request->mobile.'%');
            }

            if($request->national_id){
                $eloquentData->where('merchant_staff.national_id','LIKE','%'.$request->national_id.'%');
            }


            if($request->address){
                $eloquentData->where('merchant_staff.address','LIKE','%'.$request->address.'%');
            }

            if($request->status){
                $eloquentData->where('merchant_staff.status',$request->status);
            }

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('firstname',function($data){
                    return $data->firstname.' '.$data->lastname.' ( '.$data->merchant_staff_group_title.' )';
                })
                ->addColumn('national_id','{{$national_id}}')
                ->addColumn('merchant', function($data){
                    return '<a target="_blank" href="'.route('merchant.merchant.show',$data->merchant_id).'">'.$data->merchant_name.'</a>';
                })
                ->addColumn('lastlogin',function($data){
                    if($data->lastlogin){
                        return $data->lastlogin->diffForHumans();
                    }else{
                        return '--';
                    }
                })
                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.staff.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.staff.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.staff.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('National ID'),__('Merchant'),__('Last login'),__('action')];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Staff');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Staff');
            }

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Staff'),
            ];

            return $this->view('merchant.staff.index',$this->viewData);
        }
    }


    public function create(Request $request)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Staff'),
            'url'=> url('system/merchant/staff')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Staff'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Staff');


        $this->viewData['merchantBranchs'] = ['Select Branchs'];
        $this->viewData['merchantStaffGroup'] = ['Select Staff Group'];
            // Add Branch To Merchant With GET ID
        $merchantID = $request->merchant_id ?? old('merchant_id');
        if($merchantID){
            $merchantData = Merchant::where('id',$merchantID);

            if(!staffCan('show-tree-users-data',Auth::id())) {
                $merchantData->whereIn('staff_id', Auth::user()->managed_staff_ids());
            }

            $merchantData = $merchantData->firstOrFail();
            $this->viewData['merchantData'] = $merchantData;
            $this->viewData['merchantBranchs'] = $this->viewData['merchantBranchs']+array_column(MerchantBranch::where('merchant_id',$merchantID)->get()->toArray(),'name_'.$this->systemLang,'id');
            $this->viewData['merchantStaffGroup'] = $this->viewData['merchantStaffGroup']+array_column(MerchantStaffGroup::where('merchant_id',$merchantID)->get()->toArray(),'title','id');
        }

        return $this->view('merchant.staff.create',$this->viewData);
    }


    public function store(MerchantStaffFormRequest $request)
    {

        $request['branches'] = implode(',',$request->branches);
        $request['password'] = bcrypt($request->password);

        if(MerchantStaff::create($request->all()))
            return redirect()
                ->route('merchant.staff.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('merchant.staff.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Staff'));
        }

    }


    public function show(MerchantStaff $staff)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff->merchant()->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'url' => route('merchant.merchant.show',$staff->staff_group->merchant->id),
            'text'=> $staff->staff_group->merchant->{'name_'.$this->systemLang},
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('View Merchant Staff'),
        ];

        if(!empty($staff->branches)){
            $branches = MerchantBranch::whereIn('id',$staff->branches)->get();
        }else{
            $branches = [];
        }

        $this->viewData['branches'] = $branches;


        $this->viewData['pageTitle'] = __('Merchant Staff');
        $this->viewData['result'] = $staff;
        return $this->view('merchant.staff.show',$this->viewData);
    }


    public function edit(MerchantStaff $staff)
    {

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff->merchant()->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Staff'),
            'url'=> url('system/merchant/staff')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Staff'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Staff');
        $this->viewData['result'] = $staff;

        // Select
        $this->viewData['merchantBranchs'] = ['Select Branchs'];
        $this->viewData['merchantStaffGroup'] = ['Select Staff Group'];
        $this->viewData['merchantBranchs'] = $this->viewData['merchantBranchs']+array_column(MerchantBranch::where('merchant_id',$staff->staff_group->merchant_id)->get()->toArray(),'name_'.$this->systemLang,'id');
        $this->viewData['merchantStaffGroup'] = $this->viewData['merchantStaffGroup']+array_column(MerchantStaffGroup::where('merchant_id',$staff->staff_group->merchant_id)->get()->toArray(),'title','id');

        return $this->view('merchant.staff.create',$this->viewData);
    }


    public function update(MerchantStaffFormRequest $request,MerchantStaff $staff)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff->merchant()->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('merchant-staff');
        } else {
            unset($theRequest['image']);
        }

        if($request->password){
            $theRequest['password'] = bcrypt($request->password);
        }

        $theRequest['branches'] = implode(',',$theRequest['branches']);

        if($staff->update($theRequest)) {
            return redirect()
                ->route('merchant.staff.edit',$staff->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant Staff'));
        }else{
            return redirect()
                ->route('merchant.staff.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Staff'));
        }
    }


    public function destroy(MerchantStaff $staff,Request $request){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($staff->merchant()->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $staff->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant Staff has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.staff.index')
                ->with('status','success')
                ->with('msg',__('This staff has been deleted'));
        }
    }

}