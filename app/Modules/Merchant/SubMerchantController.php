<?php

namespace App\Modules\Merchant;


use App\Http\Requests\SubMerchantFormRequest;
use App\Libs\WalletData;
use App\Models\Merchant;
use App\Models\MerchantBranch;
use App\Models\MerchantProductCategory;
use App\Models\MerchantStaff;
use App\Models\TempData;
use App\Models\WalletTransactions;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Validator;
use App\Models\MerchantCategory;
use App\Models\AreaType;
use App\Models\MerchantPlan;
use Auth;

class SubMerchantController extends MerchantController
{

    protected $viewData = [];

    public function index(Request $request){
        $merchant = $request->user()->merchant();
        if($request->isDataTable){

            $eloquentData = Merchant::viewData($this->systemLang);

            $eloquentData->where('merchants.parent_id', '=',$merchant->id);

            whereBetween($eloquentData,'merchants.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('merchants.id', '=',$request->id);
            }

            if($request->merchant_category_id){
                $eloquentData->where('merchants.merchant_category_id', '=',$request->merchant_category_id);
            }


            if($request->merchant_plan_id){
                $eloquentData->where('merchant_plans.id', '=',$request->merchant_plan_id);
            }

            if(is_array($request->area_id) && !empty($request->area_id) && !(count($request->area_id) == 1 && $request->area_id[0] == '0') ){
                $eloquentData->where('merchants.area_id','IN',getAreasDown(last($request->area_id)));
            }
            // ---- area_id // area_id

            if($request->name){
                $name = $request->name;
                $eloquentData->where(function($query) use($name) {
                    $query->where('merchants.name_ar','LIKE','%'.$name.'%')
                        ->orWhere('merchants.name_en','LIKE','%'.$name.'%');
                });
            }

            if($request->address){
                $eloquentData->where('merchants.address','LIKE','%'.$request->address.'%');
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

            if($request->mobile){
                $mobile = $request->mobile;
                $eloquentData->where(function($query) use($mobile) {
                    $query->where('merchants.admin_mobile1','LIKE','%'.$mobile.'%')
                        ->orWhere('merchants.admin_mobile2','LIKE','%'.$mobile.'%')
                        ->orWhere('merchants.admin_phone1','LIKE','%'.$mobile.'%')
                        ->orWhere('merchants.admin_phone2','LIKE','%'.$mobile.'%')
                        ->orWhere('merchants.admin_fax1','LIKE','%'.$mobile.'%')
                        ->orWhere('merchants.admin_fax2','LIKE','%'.$mobile.'%');
                });
            }

            if($request->status){
                $eloquentData->where('merchants.status',$request->status);
            }

            if($request->staff_id){
                $eloquentData->where('merchants.staff_id',$request->staff_id);
            }


            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img src="'.asset('storage/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->category_name.') ';
                })
                ->addColumn('wallet', function($data){
                    return number_format($data->paymentwallet->balance,2).' '.__('LE');
                })
                ->addColumn('count_branches',function($data){
                    return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('Branches').'</td>
                                        <td>'.$data->count_branchs.'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Staff Groups').'</td>
                                        <td>'.$data->count_staff_group.'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('Staff').'</td>
                                        <td>'.$data->count_staff.'</td>
                                    </tr>
                                </tbody>
                            </table>';
                })

                ->addColumn('action',function($data){

                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.sub-merchant.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.sub-merchant.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.wallet.transactions',$data->paymentWallet->id)."\">".__('Transactions')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('panel.merchant.sub-merchant.dashboard',$data->id)."\">".__('Dashboard')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('panel.merchant.sub-merchant.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })

                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'table-danger';
                    }
                })
                ->make(true);

        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Logo'),__('Name'),__('Wallet'),__('Counter'),__('Action')];
            $this->viewData['pageTitle'] = __('Sub-Merchant');
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            return $this->view('sub-merchant.index',$this->viewData);
        }
    }


    public function requested(Request $request){
        $merchant = $request->user()->merchant();
        if($request->isDataTable){
            $staff = $merchant->MerchantStaff()->pluck('merchant_staff.id')->toArray();
            $eloquentData = TempData::select(['*']);

            $eloquentData->where('type','=','merchant');
            $eloquentData->whereIn('create_id', $staff);
            $eloquentData->where('create_type','=','App\Models\MerchantStaff');
            $eloquentData->whereNull('reviewed_id');
            $eloquentData->whereNull('reviewed_at');

            whereBetween($eloquentData,'temp_data.created_at',$request->created_at1,$request->created_at2);

            //dd($eloquentData->get());

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name_ar', function($data){
                    return $data->data['name_ar'];
                })
                ->addColumn('name_en', function($data){
                    return $data->data['name_en'];
                })

                ->addColumn('added_by', function($data){
                    return $data->CreatedBy->name;
                })
                ->addColumn('created_at', function($data){
                    return $data->created_at;
                })

                ->addColumn('action',function($data){
                    return "<a href=\"".route('panel.merchant.sub-merchant.requested.edit',$data->id)."\" class='btn btn-primary'><i class='ft-edit'></i></a>";
                })

                ->make(true);

        }else{

            // View Data
            $this->viewData['tableColumns'] = [__('ID'),__('Name (AR)'),__('Name (EN)'),__('Added_by'),__('created_at'),__('Action')];
            $this->viewData['pageTitle'] = __('Sub-Merchant');
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            return $this->view('sub-merchant.review',$this->viewData);
        }
    }

    public function create(Request $request)
    {
        $this->viewData['pageTitle'] = __('Create Sub-Merchant');

        $this->viewData['merchant_categories'] = array_column(MerchantCategory::get(['id',\DB::raw(" CONCAT(`name_en`,' - ',`name_ar`) as `name`")])->toArray(),'name','id');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
        //$this->viewData['merchantPlans'] = array_column( MerchantPlan::get(['id','title'])->toArray(),'title','id');

        return $this->view('sub-merchant.fast-create',$this->viewData);
    }


    public function store(Request $request){
        $merchant = $request->user()->merchant();
        $RequestData = $request->only(['name_ar','description_ar','contact','merchant_category_id','area_id','address',
            'branch_latitude','branch_longitude','staff_national_id','contractFile','contractTitle'
        ]);

        $RequestData['name_en'] = str_slug($RequestData['name_ar'],' ');
        $RequestData['description_en'] = str_slug($RequestData['description_ar'],' ');
        $RequestData['branch_name_en'] = str_slug($RequestData['name_ar'],' ').' - Main Branch';
        $RequestData['branch_address_en'] = str_slug($RequestData['address'],' ');
        $RequestData['branch_description_en'] = str_slug($RequestData['description_ar'],' ');
        $RequestData['branch_name_ar'] = $RequestData['name_ar'].' - الفرع الرئيسى';
        $RequestData['branch_address_ar'] = $RequestData['address'];
        $RequestData['branch_description_ar'] = $RequestData['description_ar'];
        $name = explode(' ',$RequestData['contact']['name'][0]);
        $RequestData['staff_firstname'] = $name[0];
        $RequestData['staff_lastname'] = $name[1].' '.((isset($name[3]))?$name[3]:null);
        $RequestData['staff_email'] = $RequestData['staff_national_id'].'@egpay.com';
        try {
            $date = str_split(substr($RequestData['staff_national_id'],1,6),2);
            if($date[0] > date('y'))
                $date[0] = '19'.$date[0];
            else
                $date[0] = '20'.$date[0];
            $RequestData['staff_birthdate'] = Carbon::createFromFormat('Y-m-d', implode('-',$date));
        } catch (\Exception $e){}

        Validator::make($RequestData,[
            'name_en'                               => 'required',
            'description_en'                        => 'required',
            'name_ar'                               => 'required',
            'description_ar'                        => 'required',
            'merchant_category_id'                  => 'numeric',
            'area_id'                               => 'required',
            'contact.name.*'                        => 'required',
            'contact.email.*'                       => 'required|email',
            'contact.mobile.*'                      => 'required|digits:11',

            //Branch validation
            'branch_name_en'                        => 'required',
            'branch_address_en'                     => 'required',
            'branch_description_en'                 => 'required',
            'branch_name_ar'                        => 'required',
            'branch_address_ar'                     => 'required',
            'branch_description_ar'                 => 'required',
            'branch_latitude'                       => 'required',
            'branch_longitude'                      => 'required',

            //Employee validation
            'staff_firstname'                    =>  'required',
            'staff_lastname'                     =>  'required',
            'staff_email'                        =>  'required|email|unique:merchant_staff,email',
            'staff_national_id'                  =>  'required|digits:14',
            //'contractTitle.*'                    =>  'required',
            //'contractFile.*'                     =>  'required|image',
        ])->validate();


        $theRequest = $RequestData;
        $theRequest['area_id'] = getLastNotEmptyItem($RequestData['area_id']);
        $theRequest['parent_id'] = $merchant->id;
        unset($theRequest['contractTitle'],$theRequest['contractFile']);


        $NewTempData = TempData::create([
            'type'          =>      'merchant',
            'data'          =>      $theRequest,
            'create_id'     =>      Auth::id(),
            'create_type'   =>      get_class(Auth::user()),

        ]);

        if($NewTempData){
            if($request->file('contractFile')) {
                foreach($request->file('contractFile') as $key=>$val){
                    $oneUploadedFile['model_id'] = $NewTempData->id;
                    $oneUploadedFile['model_type'] = get_class($NewTempData);
                    $oneUploadedFile['path'] = $val->store(MediaFiles('merchant_contracts'));
                    $oneUploadedFile['title'] = $RequestData['contractTitle'][$key];
                    $NewTempData->uploads()->insert($oneUploadedFile);
                }
            }
            return redirect()->route('panel.merchant.sub-merchant.index')
                ->with('status', 'success')
                ->with('msg', __('Merchant successfully added to be reviewed'));
        } else {
            return redirect()->route('panel.merchant.sub-merchant.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant'));
        }

    }


    public function request_edit(Request $request, TempData $id)
    {
        $tempData = $id;
        if(($tempData->reviewed_id !== null) || ($tempData->reviewed_at !== null))
            return abort(404);
        $this->viewData['pageTitle'] = __('Edit Sub-Merchant before review');

        $this->viewData['merchant_categories'] = array_column(MerchantCategory::get(['id',\DB::raw(" CONCAT(`name_en`,' - ',`name_ar`) as `name`")])->toArray(),'name','id');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
        $tempDataData = $tempData->data;
        $tempDataData['contact.name.0'] = $tempDataData['contact']['name'][0];
        $tempDataData['files'] = $tempData->uploads;
        $tempDataData['id'] = $tempData->id;
        $this->viewData['result'] = $tempDataData;
        Input::merge($tempDataData);
        return $this->view('sub-merchant.fast-create',$this->viewData);
    }

    public function request_update(Request $request,TempData $id){
        $tempData = $id;
        $merchant = $request->user()->merchant();
        $RequestData = $request->only(['name_ar','description_ar','contact','merchant_category_id','area_id','address',
            'branch_latitude','branch_longitude','staff_national_id','contractFile','contractTitle','old_contract_title','old_contract_file'
        ]);

        $RequestData['name_en'] = str_slug($RequestData['name_ar'],' ');
        $RequestData['description_en'] = str_slug($RequestData['description_ar'],' ');
        $RequestData['branch_name_en'] = str_slug($RequestData['name_ar'],' ').' - Main Branch';
        $RequestData['branch_address_en'] = str_slug($RequestData['address'],' ');
        $RequestData['branch_description_en'] = str_slug($RequestData['description_ar'],' ');
        $RequestData['branch_name_ar'] = $RequestData['name_ar'].' - الفرع الرئيسى';
        $RequestData['branch_address_ar'] = $RequestData['address'];
        $RequestData['branch_description_ar'] = $RequestData['description_ar'];
        $name = explode(' ',$RequestData['contact']['name'][0]);
        $RequestData['staff_firstname'] = $name[0];
        $RequestData['staff_lastname'] = $name[1].' '.((isset($name[3]))?$name[3]:null);
        $RequestData['staff_email'] = $RequestData['staff_national_id'].'@egpay.com';
        try {
            $date = str_split(substr($RequestData['staff_national_id'],1,6),2);
            if($date[0] > date('y'))
                $date[0] = '19'.$date[0];
            else
                $date[0] = '20'.$date[0];
            $RequestData['staff_birthdate'] = Carbon::createFromFormat('Y-m-d', implode('-',$date));
        } catch (\Exception $e){}

        Validator::make($RequestData,[
            'name_en'                               => 'required',
            'description_en'                        => 'required',
            'name_ar'                               => 'required',
            'description_ar'                        => 'required',
            'merchant_category_id'                  => 'numeric',
            'area_id'                               => 'required',
            'contact.name.*'                        => 'required',
            'contact.email.*'                       => 'required|email',
            'contact.mobile.*'                      => 'required|digits:11',

            //Branch validation
            'branch_name_en'                        => 'required',
            'branch_address_en'                     => 'required',
            'branch_description_en'                 => 'required',
            'branch_name_ar'                        => 'required',
            'branch_address_ar'                     => 'required',
            'branch_description_ar'                 => 'required',
            'branch_latitude'                       => 'required',
            'branch_longitude'                      => 'required',

            //Employee validation
            'staff_firstname'                    =>  'required',
            'staff_lastname'                     =>  'required',
            'staff_email'                        =>  'required|email|unique:merchant_staff,email',
            'staff_national_id'                  =>  'required|digits:14',
            //'contractTitle.*'                    =>  'required',
            //'contractFile.*'                     =>  'required|image',
        ])->validate();


        $theRequest = $RequestData;
        $theRequest['area_id'] = getLastNotEmptyItem($RequestData['area_id']);
        $theRequest['parent_id'] = $merchant->id;
        $uploads = $tempData->uploads();

        if(isset($request->old_contract_title)){
            $uploads->whereNotIn('path',$request->old_contract_file)->delete();
        } else {
            $uploads->delete();
        }

        if(isset($request->old_contract_title)){
            foreach($request->old_contract_title as $key=>$val){
                $path = $request->old_contract_file[$key];
                if($row = $uploads->where('path','=',$path)->first()){
                    $row->update(['title'=>$val]);
                }
            }
        }

        unset($theRequest['contractTitle'],$theRequest['contractFile'],$theRequest['old_contract_title'],$theRequest['old_contract_file']);


        $NewTempData = $tempData->update([
            'data'          =>      $theRequest,
            'create_id'     =>      Auth::id(),
        ]);

        if($NewTempData){
            if($request->file('contractFile')) {
                foreach($request->file('contractFile') as $key=>$val){
                    $oneUploadedFile['model_id'] = $tempData->id;
                    $oneUploadedFile['model_type'] = get_class($tempData);
                    $oneUploadedFile['path'] = $val->store(MediaFiles('merchant_contracts'));
                    $oneUploadedFile['title'] = $RequestData['contractTitle'][$key];
                    $tempData->uploads()->insert($oneUploadedFile);
                }
            }
            return redirect()->route('panel.merchant.sub-merchant.requested')
                ->with('status', 'success')
                ->with('msg', __('Merchant successfully Edited to be reviewed'));
        } else {
            return redirect()->route(['panel.merchant.sub-merchant.requested.edit',$tempData->id])
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant'));
        }

    }



    public function show(Merchant $sub_merchant, Request $request){
        $merchant = request()->user()->merchant();
        if ($merchant->id != $sub_merchant->parent_id)
            return abort(404);

        if($request->isBranches){

            $eloquentData = \App\Models\MerchantBranch::viewData($this->systemLang)
                ->where('merchant_branches.merchant_id',$sub_merchant->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$name}}')
                ->addColumn('address','{{str_limit($address,10)}}')
                ->addColumn('map',function($data){
                    if(!empty($data->latitude) && !empty($data->longitude)){
                        return '<a href="javascript:void(0);" onclick="viewMap('.$data->latitude.','.$data->longitude.',\''.$data->name.' ('.$data->merchant_name.') \')">View Map</a>';
                    }else{
                        return '--';
                    }
                })
                ->addColumn('status',function($data){
                    if($data->status == 'in-active'){
                        return 'tr-danger';
                    }
                })
                ->make(true);


        }elseif($request->isContract){
            $eloquentData = \App\Models\MerchantContract::viewData($this->systemLang)
                ->where('merchant_id',$sub_merchant->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('price','{{$price}} {{__(\'LE\')}}')
                ->addColumn('start_date','{{$start_date}}')
                ->addColumn('end_date','{{$end_date}}')
                ->addColumn('staff_firstname',function($data){
                    return $data->staff_firstname.' '.$data->staff_lastname;
                })
                ->make(true);
        }elseif($request->isProductCategory){
            $eloquentData = \App\Models\MerchantCategory::viewData($this->systemLang)
                ->where('merchant_id',$sub_merchant->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('icon',function($data){
                    if(!$data->icon) return '--';
                    return '<img src="'.asset('storage/app/'.imageResize($data->icon,70,70)).'" />';
                })
                ->addColumn('name','{{$name}}')
                ->addColumn('description',function ($data){
                    return str_limit($data->description,10);
                })
                ->make(true);
        }elseif($request->isStaff){
            $eloquentData = \App\Models\MerchantStaff::viewData($this->systemLang)
                ->where('merchant_staff_group.merchant_id',$sub_merchant->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$username}}')
                ->addColumn('firstname','{{$firstname}} {{$lastname}}')
                ->addColumn('merchant_staff_group_title','{{$sub_merchant_staff_group_title}}')
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
                ->make(true);
        } else {

            $this->viewData['pageTitle'] = __('View Merchant') . ' ' . $sub_merchant->{'name_' . $this->systemLang};
            $this->viewData['merchantStaffGroups'] = $sub_merchant->MerchantStaffGroup;
            $this->viewData['productsCategories'] = $sub_merchant->productCategories;
            $this->viewData['result'] = $sub_merchant;

            $MerchantStaffGroup = $sub_merchant->MerchantStaffGroup;

            if($MerchantStaffGroup->isNotEmpty()){
                $this->viewData['merchantStaffGroups'] = $MerchantStaffGroup;
                $MerchantStaffGroupIDs = array_column($MerchantStaffGroup->toArray(),'id');
                $merchantStaff = MerchantStaff::whereIn('merchant_staff_group_id',$MerchantStaffGroupIDs)->get();

                foreach ($merchantStaff as $key => $value){
                    $this->viewData['merchantStaff'][$value->merchant_staff_group_id][] = $value;
                }
            }

            return $this->view('sub-merchant.show', $this->viewData);
        }
    }

    public function edit(Request $request, Merchant $sub_merchant){
        $merchant = $request->user()->merchant();
        if($merchant->id !== $sub_merchant->parent_id)
            return redirect()->route('panel.merchant.home');

        $this->viewData['pageTitle'] = __('Edit Sub-Merchant').' '.$sub_merchant->name_en.' - '.$sub_merchant->name_ar;
        $this->viewData['merchant_categories'] = array_column(MerchantCategory::get(['id',\DB::raw(" CONCAT(`name_en`,' - ',`name_ar`) as `name`")])->toArray(),'name','id');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);

        $this->viewData['result'] = $sub_merchant;

        return $this->view('sub-merchant.create',$this->viewData);
    }


    public function update(SubMerchantFormRequest $request,Merchant $sub_merchant)
    {
        $merchant = $request->user()->merchant();
        if ($merchant->id != $sub_merchant->parent_id)
            return redirect()->route('panel.merchant.home');

        $RequestData = $request->only(['name_en', 'description_en', 'name_ar', 'description_ar', 'merchant_category_id', 'address', 'area_id', 'logo', 'contact']);

        $theRequest = $RequestData;
        $theRequest['area_id'] = (int) getLastNotEmptyItem($RequestData['area_id']);

        if ($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('public/merchant/logo');
        } elseif ($request->logo){
            $theRequest['logo'] = $request->logo;
        }else{
            unset($theRequest['logo']);
        }


        if(isset($theRequest['contact'])){
            $contacts = [];
            $now = Carbon::now();
            $sub_merchant->contactmodel()->delete();
            foreach($theRequest['contact'] as $key=>$values){
                foreach($values as $contactVal){
                    $contacts[] = [
                        'type'          =>   $key,
                        'value'         =>   $contactVal,
                        'model_type'    =>   'App\Models\Merchant',
                        'model_id'      =>   $sub_merchant->id,
                        'created_at'    =>  $now,
                    ];
                }
            }
                $sub_merchant->contactmodel()->insert($contacts);
        }

        $update = $sub_merchant->update($theRequest);

        if($update) {
            return redirect()->route('panel.merchant.sub-merchant.index')
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant'));
        }else{
            return redirect()->route('panel.merchant.sub-merchant.edit')
                ->with('status','success')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant'));
        }
    }



    public function destroy(Merchant $sub_merchant,Request $request){
        $merchant = $request->user()->merchant();
        if($merchant->id != $sub_merchant->parent_id) {;
            return redirect()->route('panel.merchant.home');
        }
        // Delete Data
        $sub_merchant->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Category has been deleted successfully')];
        }else{
            return redirect()
                ->route('panel.merchant.sub-merchant.index')
                ->with('status','success')
                ->with('msg',__('Category has been deleted'));
        }
    }

    public function dashboard($id){
        $merchant = Merchant::where('parent_id','=',Auth::user()->merchant()->id)
            ->where('id','=',$id)
            ->withCount(['merchant_branch','merchant_products','payment_invoice'])->first();
        $year = date('Y');
        $merchant['transaction_from_count'] = array_sum(recursiveFind($merchant->wallet->toArray(),'transaction_from_count'));
        $merchant['transaction_to_count'] = array_sum(recursiveFind($merchant->wallet->toArray(),'transaction_to_count'));
        $merchant['transactions'] = $merchant['transaction_from_count'] + $merchant['transaction_to_count'];

        $this->viewData['pageTitle'] = __('Merchant Dashboard');
        $this->viewData['merchant'] = $merchant;
        $this->viewData['merchant_branches'] = $merchant->merchant_branch()
            ->select(['id','name_'.$this->systemLang.' AS name'])
            ->with(['orders'=>function($query){
                $query->select('id','merchant_branch_id');
                $query->selectRaw('SUM(total) AS orders_total');
                $query->selectRaw('CONCAT(YEAR(created_at),\'-\',MONTH(created_at)) AS date');
                $query->where('is_paid','=','yes');
                $query->groupBy('date');
            }])
            ->get();

        $this->viewData['merchant_invoice'] = Merchant::where('parent_id','=',Auth::user()->merchant()->id)
            ->where('id','=',$id)
            ->select(['id'])
            ->where('id',$merchant->id)
            ->with(['payment_invoice'=>function($query){
                $query->select(['creatable_id','creatable_type','status']);
                $query->selectRaw('DATE_FORMAT(`created_at`,\'%b - %Y\') AS month');
                $query->selectRaw('SUM(total_amount) AS total_amount');
                $query->selectRaw('CONCAT(YEAR(created_at),\'-\',MONTH(created_at)) AS date');
                $query->groupBy(['date','status']);
            }])
            ->first()->payment_invoice;

        $this->viewData['months'] = array_map(function($month)use($year){return $year.'-'.(($month<10)?'0'.$month:$month);},range(1,12));
        return $this->view('sub-merchant.dashboard',$this->viewData);
    }

    public function Report(Request $request){
        $merchant = Auth::user()->merchant();

        if($request->from_date && $request->to_date && $request->merchant_id){
            $this->validate($request,[
                'merchant_id'       =>  'required',
                'from_date'          =>  'required',
                'to_date'            =>  'required',
            ]);
            $wallet = $merchant->with(['child'=>function($sql)use($request){
                $sql->where('id','=',$request->merchant_id);
            }])->first()->paymentWallet;


            $this->viewData['result'] = $wallet;
            $transCount = $wallet->allTransaction();
            whereBetween($transCount,'created_at',$request->from_Date,$request->to_date);
            $this->viewData['transactions'] = $transCount->get();

        }

        $this->viewData['pageTitle'] = __('Sub-Merchant Report');
        $this->viewData['submerchants'] = $merchant->child()->pluck('name_'.$this->systemLang,'id')->toArray();
        return $this->view('sub-merchant.report',$this->viewData);
    }

}