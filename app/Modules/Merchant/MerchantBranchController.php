<?php

namespace App\Modules\Merchant;

use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\MerchantBranch;
use App\Models\MerchantPlan;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use App\Models\MerchantCategory;
use Illuminate\Support\Facades\Validator;
use Auth;

class MerchantBranchController extends MerchantController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $merchant = $request->user()->merchant();

        if($request->isDataTable){
            $eloquentData = MerchantBranch::viewData($this->systemLang);

            $eloquentData->where('merchant_branches.merchant_id',$merchant->id);


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
                $eloquentData->where('merchant_branches.area_id','IN',\App\Libs\AreasData::getAreasDown($request->area_id));
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
            $eloquentData->groupBy('merchant_branches.id');

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
                        return '<a href="javascript:void(0);" onclick="viewMap('.$data->latitude.','.$data->longitude.',\''.$data->name.' ('.$data->merchant_name.') \')">'.__('View Map').'</a>';
                    }else{
                        return '--';
                    }
                })
                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.branch.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.branch.edit',$data->id)."\">".__('Edit')."</a></li>
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
            $this->viewData['tableColumns'] = [__('ID'),__('Logo'),__('Name'),__('Address'),__('Map'),__('Action')];



            $this->viewData['pageTitle'] = __('Merchant Branches');

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


            return $this->view('branches.index',$this->viewData);
        }
    }

    public function create()
    {
        $this->viewData['pageTitle'] = __('Create Merchant Branch');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);

        $this->viewData['merchantData'] = request()->user()->merchant();


        return $this->view('branches.create',$this->viewData);
    }


    public function store(Request $request){
        $RequestData = $request->only(['name_en','address_en','description_en','name_ar','address_ar','description_ar','latitude','longitude','area_id','status']);

        Validator::make($RequestData, [
            'name_en' => 'required',
            'address_en' => 'required',
            'description_en' => 'required',
            'name_ar' => 'required',
            'address_ar' => 'required',
            'description_ar' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'area_id' => 'required',
            'status' => 'required|in:active,in-active'
        ])->validate();


        $merchant = request()->user()->merchant();

        $RequestData['area_id'] = getLastNotEmptyItem($RequestData['area_id']);
        $RequestData['merchant_id'] = $merchant->id;
        $RequestData['merchant_staff_id'] = Auth::id();

        if(MerchantBranch::create($RequestData))
            return redirect()
                ->route('panel.merchant.branch.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('panel.merchant.branch.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant Branch'));
        }
    }

    public function show(MerchantBranch $branch,Request $request)
    {
        $merchant = $request->user()->merchant();
        if($merchant->id != $branch->merchant_id)
            return redirect()->route('panel.merchant.home');

        if($request->orders){
            $eloquentData = Order::viewBranchOrders($this->systemLang);

            $eloquentData->where('merchant_branches.id', '=',$branch->id);

            whereBetween($eloquentData,'orders.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('orders.id', '=',$request->id);
            }


            if($request->totalmin){
                $eloquentData->where('orders.total', '>=',$request->totalmin);
            }

            if($request->totalmax){
                $eloquentData->where('orders.total', '<=',$request->totalmin);
            }

            if($request->status){
                $eloquentData->where('orders.is_paid',$request->status);
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('amount', function($data){
                    return $data->total.' '.__('LE');
                })
                ->addColumn('created_at', function($data){
                    return $data->created_at.' ('.$data->created_at->diffForHumans().')';
                })
                ->addColumn('action',function($data){
                    //return "<a class='btn btn-primary' href='javascript:void(0);' onclick='urlIframe(\"".route('panel.merchant.order.show',$data->id)."\")'><i class='ft-eye'></i></a>";
                    return "<a class='btn btn-primary' href='".route('panel.merchant.order.show',$data->id)."'><i class='ft-eye'></i></a>";
                })

                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'table-danger';
                    }
                })
                ->make(true);
        }
        $this->viewData['pageTitle'] = __('Branch Information');
        $this->viewData['branch'] = $branch;
        $this->viewData['lang'] = $this->systemLang;

        return $this->view('branches.view',$this->viewData);
    }


    public function edit(Request $request,MerchantBranch $branch){
        $merchant = $request->user()->merchant();
        if($merchant->id != $branch->merchant_id)
            return redirect()->route('panel.merchant.home');

        $this->viewData['pageTitle'] = __('Edit Merchant Branch: ').$branch->name_en.' - '.$branch->name_ar;

        $this->viewData['result'] = $branch;

        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);

        return $this->view('branches.create',$this->viewData);
    }


    public function update(Request $request,MerchantBranch $branch)
    {
        $merchant = $request->user()->merchant();
        if($merchant->id != $branch->merchant_id) {;
            return redirect()->route('panel.merchant.home');
        }
        $RequestData = $request->only(['name_en','address_en','description_en','name_ar','address_ar','description_ar','latitude','longitude','area_id','status']);
        Validator::make($RequestData, [
            'name_en' => 'required',
            'address_en' => 'required',
            'description_en' => 'required',
            'name_ar' => 'required',
            'address_ar' => 'required',
            'description_ar' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'area_id' => 'required',
            'status' => 'required|in:active,in-active'
        ])->validate();

        $RequestData['area_id'] = getLastNotEmptyItem($RequestData['area_id']);
        if($branch->update($RequestData)) {
            return redirect()->route('panel.merchant.branch.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant branch'));
        }else{
            return redirect()->route('panel.merchant.branch.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant Branch'));
        }
    }



    public function destroy(MerchantBranch $branch,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $branch->id) {;
            return redirect()->route('panel.merchant.home');
        }
        // Delete Data
        $branch->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Branch has been deleted successfully')];
        }else{
            return redirect()
                ->route('panel.merchant.branch.index')
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