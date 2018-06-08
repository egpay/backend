<?php

namespace App\Modules\Merchant;


use App\Models\MerchantStaffGroup;
use App\Models\MerchantStaffPermission;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantStaffGroupController extends MerchantController
{

    protected $viewData = [];

    public function index(Request $request){

        $merchant = $request->user()->merchant();
        if($request->isDataTable){
            $eloquentData = MerchantStaffGroup::viewData($this->systemLang);

            $eloquentData->where('merchant_staff_groups.merchant_id','=',$merchant->id);

            whereBetween($eloquentData,'merchant_staff_groups.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchant_staff_groups.id','=',$request->id);
            }

            if($request->title){
                $eloquentData->where('merchant_staff_groups.title','like','%'.$request->title.'%');
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('title',function($data){
                    return $data->title;
                })
                ->addColumn('count',function($data){
                    if($data->merchant_staff_count > 0){
                        return link_to_route('panel.merchant.employee.index',$data->merchant_staff_count,['merchant_staff_group_id'=>$data->id]);
                    } else
                        return $data->merchant_staff_count;
                })

                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.staff-group.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.staff-group.edit',$data->id)."\">".__('Edit')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }else{

            // View Data
            $this->viewData['tableColumns'] = ['ID',__('Group Title'),__('Staff Count'),__('Action')];
            $this->viewData['pageTitle'] = __('Merchant staff groups');

            return $this->view('staffgroup.index',$this->viewData);
        }
    }

    public function create(Request $request)
    {
        $this->viewData['pageTitle'] = __('Create Staff Group');
        $this->viewData['permissions'] = $this->permissions();
        return $this->view('staffgroup.create',$this->viewData);
    }


    public function store(Request $request)
    {
        $merchant = $request->user()->merchant();
        $merchantstaffgroup = $request->only('title');
        Validator::make($merchantstaffgroup, [
            'title' => 'required',
        ])->validate();

        $permissions = array();
        $perms = recursiveFind($this->permissions(),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                $permissions[$key] = $oneperm;
            }
        }

        $coll = new Collection();
        $merchantstaffgroup['merchant_id'] = $merchant->id;

        if($row = MerchantStaffGroup::create($merchantstaffgroup)){
            array_map(function($oneperm)use ($permissions,$row,&$coll){
                foreach ($permissions[$oneperm] as $oneroute){
                    $coll->push(new MerchantStaffPermission(['route_name'=>$oneroute,'merchant_staff_group_id'=>$row->id]));
                }
            },$request->all()['permissions']);
            $row->merchant_staff_permission()->saveMany($coll);

            return redirect()->route('panel.merchant.staff-group.index')
                ->with('status', 'success')
                ->with('msg', __('Permission Group added'));
        } else{
            return redirect()->route('panel.merchant.staff_group.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Permission Group'));
        }

    }

    public function show(Request $request, MerchantStaffGroup $staff_group){
        $merchant = $request->user()->merchant();
        if($merchant->id != $staff_group->merchant_id)
            return redirect()->route('panel.merchant.home');

        $staff_group->withCount('merchant_staff');
        $permissions = array();
        $perms = recursiveFind($this->permissions(),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                $permissions[$key] = $oneperm;
            }
        }
        $this->viewData['permissions'] = $permissions;
        $this->viewData['merchant'] = $merchant;
        $this->viewData['currentpermissions'] = $staff_group->merchant_staff_permission->pluck('route_name')->toArray();
        $this->viewData['pageTitle'] = __('Merchant staff group');
        $this->viewData['result'] = $staff_group;

        return $this->view('staffgroup.view',$this->viewData);
    }

    public function edit(Request $request, MerchantStaffGroup $staff_group){
        $merchant = $request->user()->merchant();
        if($merchant->id != $staff_group->merchant_id)
            return redirect()->route('panel.merchant.home');

        $this->viewData['pageTitle'] = __('Edit Staff Group');


        $this->viewData['result'] = $staff_group;
        $this->viewData['permissions'] = $this->permissions();
        $this->viewData['merchant'] = $merchant;
        $this->viewData['currentpermissions'] = $staff_group->merchant_staff_permission->pluck('route_name')->toArray();

        return $this->view('staffgroup.create',$this->viewData);
    }


    public function update(Request $request,MerchantStaffGroup $staff_group)
    {
        $merchant = $request->user()->merchant();
        if($merchant->id != $staff_group->merchant_id)
            redirect()->route('panel.merchant.home');

        $permissions = array();
        $perms = recursiveFind($this->permissions(),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                $permissions[$key] = $oneperm;
            }
        }
        $coll = new Collection();
        array_map(function($oneperm)use ($permissions,&$coll,$staff_group){
                foreach ($permissions[$oneperm] as $oneroute) {
                    $coll->push(new MerchantStaffPermission(['route_name' => $oneroute, 'merchant_staff_group_id' => $staff_group->id]));
                }
        },$request->all()['permissions']);

        if($staff_group->update($request->all())) {
            $staff_group->merchant_staff_permission()->delete();
            $staff_group->merchant_staff_permission()->saveMany($coll);

            return redirect()->route('panel.merchant.staff-group.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Category'));
        }else{
            return redirect()->route('panel.merchant.staff-group.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Category'));
        }

    }



    public function destroy(MerchantStaffGroup $staff_group,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $staff_group->merchant_id) {;
            redirect()->route('panel.merchant.home');
        }
        // Delete Data
        if($merchant->merchant_staff_group()->first()->id == $staff_group->id) {
            if($request->ajax()){
                return ['status'=> false,'msg'=> __('Merchant staff group Can\'t be deleted')];
            }else{
                return redirect()
                    ->route('panel.merchant.staff-group.index')
                    ->with('status','danger')
                    ->with('msg',__('Merchant staff group Can\'t be deleted'));
            }
        }
        $staff_group->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant staff group has been deleted successfully')];
        }else{
            return redirect()
                ->route('panel.merchant.staff-group.index')
                ->with('status','success')
                ->with('msg',__('Category has been deleted'));
        }
    }


}