<?php

namespace App\Modules\Merchant;

use App\Libs\SMS;
use App\Models\MerchantStaff;
use App\Models\MerchantStaffGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantStaffController extends MerchantController
{

    protected $viewData = [];

    public function index(Request $request){
        $merchant = $request->user()->merchant();
        if($request->isDataTable){
            $eloquentData = MerchantStaff::viewData($this->systemLang);

            whereBetween($eloquentData,'merchant_staff.created_at',$request->created_at1,$request->created_at2);

            $eloquentData->where('merchant_staff_groups.merchant_id','=',$merchant->id);

            if($request->id){
                $eloquentData->where('merchant_staff.id','=',$request->id);
            }

            if($request->name){
                $eloquentData->where(function($sql)use($request){
                    $sql->where('merchant_staff.firstname','like','%'.$request->name.'%')
                        ->orWhere('merchant_staff.lastname','like','%'.$request->name.'%');
                });
            }

            if($request->national_id){
                $eloquentData->where('merchant_staff.national_id','like','%'.$request->national_id.'%');
            }

            if($request->email){
                $eloquentData->where('merchant_staff.email','like','%'.$request->email.'%');
            }

            if($request->merchant_staff_group_id){
                $eloquentData->where('merchant_staff.merchant_staff_group_id','=',$request->merchant_staff_group_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name',function($data){
                    return $data->firstname.' '.$data->lastname;
                })

                ->addColumn('national_id',function($data){
                    return $data->national_id;
                })
                ->addColumn('email',function($data){
                    return $data->email;
                })
                ->addColumn('title',function($data){
                    return "<a href='".route('panel.merchant.staff-group.show',$data->merchant_staff_group_id)."'>$data->merchant_staff_group_title</a>";
                })
                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.employee.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.employee.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.employee.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name'),__('National ID'),__('Email'),__('Group'),__('Action')];
            $this->viewData['pageTitle'] = __('Employee');
            $this->viewData['groups'] = $merchant->MerchantStaffGroup()->pluck('title','id')->reverse()->put('0',__('Select Group'))->reverse()->toArray();


            return $this->view('employee.index',$this->viewData);
        }
    }

    public function create(Request $request)
    {
        $merchant = $request->user()->merchant();
        $this->viewData['merchant'] = $merchant;
        $this->viewData['staff_group'] = array_column( $merchant->merchant_staff_group()->get(['id','title'])->toArray(),'title','id');

        $this->viewData['branches'] = array_column($merchant->merchant_branch()->get(['id','name_'.$this->systemLang.' AS name'])->toArray(),'name','id');

        $this->viewData['pageTitle'] = __('Create Employee Account');
        return $this->view('employee.create',$this->viewData);
    }


    public function store(Request $request)
    {
        $merchant = $request->user()->merchant();
        $staffgroups = $merchant->merchant_staff_group()->pluck('id')->toArray();
        if(!in_array($request->merchant_staff_group_id, $staffgroups)){
            return redirect()->route('panel.merchant.home');
        }

        $RequestData = $request->only(['firstname','lastname','email','national_id','merchant_staff_group_id','password','password_confirmation','status','branches']);

        Validator::make($RequestData, [
            'firstname'                 =>  'required',
            'lastname'                  =>  'required',
            'email'                     =>  'required',
            'national_id'               =>  'required|digits:14',
            'merchant_staff_group_id'   =>  'required|numeric',
            'branches'                  =>  'required|array',
            'status'                    =>  'required|in:active,in-active',
        ])->validate();

        if(isset($RequestData['branches'])){
            $nbranches = [];
            $merchantbranches = $merchant->merchant_branch()->pluck('id')->toArray();
            foreach($RequestData['branches'] as $branchid){
                if(in_array($branchid,$merchantbranches))
                    $nbranches[] = $branchid;
            }
            $RequestData['branches'] = implode(',',$nbranches);
        } else {
            $RequestData['branches'] = '';
        }

        if(!in_array($RequestData['merchant_staff_group_id'],$staffgroups)){
            return redirect()->route('panel.merchant.employee.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant employee'));
        }

        $RequestData['password'] = rand(100000,999999);
        $therequest = $RequestData;
        $therequest['password'] = bcrypt($therequest['password']);



        if($MerchantStaff = MerchantStaff::create($therequest)) {
            $SMS = new SMS();
            $SMS->Send($MerchantStaff->mobile,SMS::GenerateMsg([$MerchantStaff->id,$RequestData['password'],$MerchantStaff->merchant()->id],setting('msg_merchantstaff_created')));
            return redirect()->route('panel.merchant.employee.index');
        }else{
            return redirect()->route('panel.merchant.employee.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant employee'));
        }

    }

    public function show(MerchantStaff $employee){
        $this->viewData['pageTitle'] = __('Show Employee Info');
        $this->viewData['result'] = $employee;
        $permissions = array();
        $perms = recursiveFind($this->permissions(),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                $permissions[$key] = $oneperm;
            }
        }
        $this->viewData['permissions'] = $permissions;
        $this->viewData['currentpermissions'] = $employee->staff_group->merchant_staff_permission->pluck('route_name')->toArray();

        return $this->view('employee.view',$this->viewData);
    }

    public function edit(Request $request, MerchantStaff $employee){

        $merchant = $request->user()->merchant();
        if($merchant->id != $employee->merchant()->id)
            return redirect()->route('panel.merchant.home');


        $this->viewData['pageTitle'] = __('Edit Employee info');
        $this->viewData['result'] = $employee;
        $this->viewData['staff_group'] = array_column( $merchant->merchant_staff_group()->get(['id','title'])->toArray(),'title','id');
        $this->viewData['merchant'] = $merchant;
        $this->viewData['branches'] = array_column($merchant->merchant_branch()->get(['id','name_'.$this->systemLang.' AS name'])->toArray(),'name','id');


        return $this->view('employee.create',$this->viewData);
    }


    public function update(Request $request,MerchantStaff $employee)
    {
        $merchant = $request->user()->merchant();
        $staffgroups = $merchant->merchant_staff_group()->get(['id'])->toArray();
        if($merchant->id != $employee->merchant()->id && (!in_array($request->merchant_staff_group_id, $staffgroups))) {;
            return redirect()->route('panel.merchant.home');
        }


        $RequestData = $request->only(['firstname','lastname','email','national_id','merchant_staff_group_id','password','password_confirmation','status','branches']);
        Validator::make($RequestData, [
            'firstname'                 =>  'required',
            'lastname'                  =>  'required',
            'email'                     =>  'required',
            'national_id'               =>  'required|digits:14',
            'merchant_staff_group_id'   =>  'required|numeric',
            'branches'                  =>  'required|array',
            'status'                    =>  'required|in:active,in-active',
            'password'                  =>  'nullable|confirmed',
        ])->validate();

        if(!isset($RequestData['password']))
            $RequestData['password'] = null;
        else{
            $RequestData['password'] = Hash::make($RequestData->password);
        }

        if(isset($RequestData['branches'])){
            $nbranches = [];
            $merchantbranches = $merchant->merchant_branch()->pluck('id')->toArray();
            foreach($RequestData['branches'] as $branchid){
                if(in_array($branchid,$merchantbranches))
                    $nbranches[] = $branchid;
            }
            $RequestData['branches'] = implode(',',$nbranches);
        } else {
            $RequestData['branches'] = '';
        }

        $therequest = array_filter($RequestData,function($val){return !is_null($val);});
        $therequest['merchant_staff_group_id'] = (int) $therequest['merchant_staff_group_id'];


        //Disable change status or user group for first user
        if($merchant->MerchantStaff()->first()->id == $employee->id){
            unset($therequest['status'],$therequest['merchant_staff_group_id']);
        }

        if($employee->update($therequest)) {
            return redirect()->route('panel.merchant.employee.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Employee'));
        }else{
            return redirect()->route('panel.merchant.employee.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Employee'));
        }
    }



    public function destroy(MerchantStaff $employee,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $employee->merchant_staff_group->merchant_id) {;
            return redirect()->route('panel.merchant.home');
        }
        //Disable change status or user group for first user
        if($merchant->MerchantStaff()->first()->id != $employee->id){
            $status = $employee->delete();
        }
        // Delete Data
        if($status) {
            if ($request->ajax()) {
                return ['status' => true, 'msg' => __('Merchant staff has been deleted successfully')];
            } else {
                redirect()
                    ->route('panel.merchant.employee.index')
                    ->with('status', 'success')
                    ->with('msg', __('Merchant staff has been deleted'));
            }
        } else {
            if ($request->ajax()) {
                return ['status' => true, 'msg' => __('Merchant staff Could not be deleted')];
            } else {
                redirect()
                    ->route('panel.merchant.employee.index')
                    ->with('status', 'success')
                    ->with('msg', __('Merchant staff Could not be deleted'));
            }
        }
    }

}