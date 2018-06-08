<?php

namespace App\Modules\System;

use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\MerchantBranch;
use App\Models\MerchantPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Config;
use Yajra\Datatables\Facades\Datatables;
use App\Models\MerchantCategory;
use App\Http\Requests\MerchantBranchFormRequest;
use Auth;
use App\Models\MerchantStaff;

class MerchantBranchController extends SystemController
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        if($request->isDataTable){

            $eloquentData = MerchantBranch::viewData($this->systemLang);

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }


            whereBetween($eloquentData,'merchant_branches.created_at',$request->created_at1,$request->created_at2);
            if($request->id){
                $eloquentData->where('merchant_branches.id', '=',$request->id);
            }


            if($request->merchant_id){
                $eloquentData->where('merchant_branches.merchant_id',$request->merchant_id);
            }

            if($request->name){
                $name = $request->name;
                $eloquentData->where(function($query) use($name) {
                    $query->where(DB::raw("CONCAT(merchant_branches.name_ar,' ',merchants.name_ar)"),'LIKE',"%$name%")
                        ->orWhere(DB::raw("CONCAT(merchant_branches.name_en,' ',merchants.name_en)"),'LIKE',"%$name%");
                });
            }


            if($request->mobile){
                $mobile = $request->mobile;
                $eloquentData->where(function($query) use($mobile) {
                    $query->where('merchant_branches.admin_mobile1','LIKE','%'.$mobile.'%')
                        ->orWhere('merchant_branches.admin_mobile2','LIKE','%'.$mobile.'%')
                        ->orWhere('merchant_branches.admin_phone1','LIKE','%'.$mobile.'%')
                        ->orWhere('merchant_branches.admin_phone2','LIKE','%'.$mobile.'%')
                        ->orWhere('merchant_branches.admin_fax1','LIKE','%'.$mobile.'%')
                        ->orWhere('merchant_branches.admin_fax2','LIKE','%'.$mobile.'%');
                });
            }

            if($request->address){
                $address = $request->address;
                $eloquentData->where(function($query) use($address){
                    $query->where('merchant_branches.address_ar','LIKE',"%$address%")
                        ->orWhere('merchant_branches.address_en','LIKE',"%$address%");
                });
            }


            if($request->merchant_plan_id){
                $eloquentData->where('merchant_plans.id', '=',$request->merchant_plan_id);
            }

            if(is_array($request->area_id) && !empty($request->area_id) && !(count($request->area_id) == 1 && $request->area_id[0] == '0') ){
                $eloquentData->where('merchant_branches.area_id','IN',\App\Libs\AreasData::getAreasDown(last($request->area_id)));
            }

            if($request->staff_id){
                $eloquentData->where('merchant_branches.staff_id',$request->staff_id);
            }

            // ---- Merchant Filter
            if($request->merchant_category_id){
                $eloquentData->where('merchants.merchant_category_id', '=',$request->merchant_category_id);
            }

            if($request->admin_name){
                $eloquentData->where('merchants.admin_name','LIKE','%'.$request->admin_name.'%');
            }


            if($request->admin_job_title){
                $eloquentData->where('merchants.admin_job_title','LIKE','%'.$request->admin_job_title.'%');
            }

            if($request->admin_email){
                $eloquentData->where('merchants.admin_email','LIKE','%'.$request->admin_email.'%');
            }


            if($request->status){
                $eloquentData->where('merchants.status',$request->status);
            }

            // Supervisor
            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->merchant_name.') ';
                })
                ->addColumn('address','{{str_limit($address,10)}}')
                ->addColumn('map',function($data){
                    if(!empty($data->latitude) && !empty($data->longitude)){
                        return '<a href="javascript:void(0);" onclick="viewMap('.$data->latitude.','.$data->longitude.',\''.$data->name.' ('.$data->merchant_name.') \')">View Map</a>';
                    }else{
                        return '--';
                    }
                })
                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.branch.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.branch.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.branch.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
            $this->viewData['tableColumns'] = ['ID','Logo','Name','Address','Map','Action'];


            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchant Branches');
            }else{
                $this->viewData['pageTitle'] = __('Merchant Branches');
            }

            // Filter Data
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name']);
            if($MerchantCategory->isNotEmpty()){
                $this->viewData['merchantCategories'] = array_merge(['Select Category'],array_column($MerchantCategory->toArray(),'name','id'));
            }else{
                $this->viweData['merchantCategories'] = [__('Select Category')];
            }
            // Filter Data

            $merchantPlans =  MerchantPlan::get(['id','title'])->toArray();
            $merchantPlans = array_merge([['id'=>0,'title'=>__('Select Plan')]],$merchantPlans);
            $this->viewData['merchantPlans'] = $merchantPlans;

            $this->viewData['breadcrumb'][] = [
                'text'=> __('Merchant Branches'),
            ];

            return $this->view('merchant.branch.index',$this->viewData);
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
            'text'=> __('Merchant Branches'),
            'url'=> url('system/merchant/branch')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant Branche'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant Branche');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);


        // Add Branch To Merchant With GET ID
        $merchantID = request('merchant_id');
        if($merchantID){
            $merchantData = Merchant::where('id',$merchantID)
                ->whereIn('staff_id',Auth::user()->managed_staff_ids())
                ->firstOrFail();

            $this->viewData['merchantData'] = $merchantData;
        }


        return $this->view('merchant.branch.create',$this->viewData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantBranchFormRequest $request){
        //TODO Insert loggedin user id

        $request['staff_id'] = Auth::id();
        $request['area_id'] = getLastNotEmptyItem($request->area_id);

        if(MerchantBranch::create($request->all()))
            return redirect()
                ->route('merchant.branch.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('merchant.branch.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Branch'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(MerchantBranch $branch)
    {

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($branch->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Merchants'),
                'url'=> route('merchant.merchant.index'),
            ],
            [
                'text'=> $branch->merchant->{'name_'.$this->systemLang},
                'url'=> route('merchant.merchant.show',$branch->merchant->id),
            ],
            [
                'text'=>  $branch->{'name_'.$this->systemLang},
            ]
        ];

        // -- Staff
        $this->viewData['merchantStaffGroups'] = [];
        $this->viewData['merchantStaff'] = [];

        $MerchantStaffGroup = $branch->merchant->MerchantStaffGroup;

        if($MerchantStaffGroup->isNotEmpty()){
            $this->viewData['merchantStaffGroups'] = $MerchantStaffGroup;
            $MerchantStaffGroupIDs = array_column($MerchantStaffGroup->toArray(),'id');
            $merchantStaff = MerchantStaff::whereIn('id',$MerchantStaffGroupIDs)
                ->whereRaw('FIND_IN_SET(?,`branches`)',[$branch->id])
                ->get();

            foreach ($merchantStaff as $key => $value){
                $this->viewData['merchantStaff'][$value->merchant_staff_group_id][] = $value;
            }
        }

        // -- Staff

        $this->viewData['pageTitle'] = $branch->{'name_'.$this->systemLang};
        $this->viewData['result'] = $branch;

        return $this->view('merchant.branch.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(MerchantBranch $branch){

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($branch->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Merchant Branches'),
            'url'=> url('system/merchant/branch')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant Branche'),
        ];
        $this->viewData['pageTitle'] = __('Edit Merchant Branche');

        $this->viewData['result'] = $branch;

        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
        return $this->view('merchant.branch.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(MerchantBranchFormRequest $request,MerchantBranch $branch)
    {

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($branch->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $request['area_id'] = getLastNotEmptyItem($request->area_id);
        if($branch->update($request->all())) {
            return redirect()->route('merchant.branch.edit',$branch->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant branch'));
        }else{
            return redirect()->route('merchant.branch.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Branch'));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(MerchantBranch $branch,Request $request){

        // Supervisor
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($branch->merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        // Delete Data
        $branch->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Branch has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.branch.index')
                ->with('status','success')
                ->with('msg',__('This branch has been deleted'));
        }
    }


    public function ajax(Request $request){
        $type = $request->type;
        switch ($type){
            case 'users':
                $name = $request->search;

                $data = User::whereNull('parent_id')
                        ->where(function($query){
                           // $query->where()
                        });

                break;
        }
    }


}
