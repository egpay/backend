<?php

namespace App\Modules\System;

use App\Libs\AreasData;
use App\Libs\Create;
use App\Libs\SMS;
use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\MerchantCategory;
use App\Models\MerchantPlan;
use App\Models\MerchantStaff;
use App\Models\Staff;
use App\Models\TempData;
use Carbon\Carbon;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Facades\Datatables;
use Form;
use Auth;
use App\Http\Requests\MerchantFormRequest;
use Illuminate\Support\Collection;
use App\Models\Contacts;
use App\Models\MerchantProduct;
class MerchantController extends SystemController
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

            $eloquentData = Merchant::viewData($this->systemLang)->groupBy('merchants.id');

            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

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
                $eloquentData->where('merchants.area_id','IN',\App\Libs\AreasData::getAreasDown(last($request->area_id)));
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

            if(($request->status) && in_array($request->status,['active','in-active'])){
                $eloquentData->where('merchants.status',$request->status);
            }

            if($request->staff_id){
                $eloquentData->where('merchants.staff_id',$request->staff_id);
            }

            if(!staffCan('show-tree-users-data',Auth::id())){
                $eloquentData->whereIn('merchants.staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('logo',function($data){
                    if(!$data->logo) return '--';
                    return '<img style="width:70px; height:70px;" src="'.asset('storage/app/'.imageResize($data->logo,70,70)).'" />';
                })
                ->addColumn('name', function($data){
                    return $data->name.' ('.$data->category_name.') ';
                })

                ->addColumn('count_staff',function($data){
                    return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('Payment').'</td>
                                        <td>'.amount($data->paymentWallet->balance,true).'</td>
                                    </tr>
                                    <tr>
                                        <td>'.__('eCommerce').'</td>
                                        <td>'.amount($data->eCommerceWallet->balance,true).'</td>
                                    </tr>
                                   
                                </tbody>
                            </table>';
                })

                ->addColumn('count_branchs',function($data){
                    return '<table class="table">
                                <tbody>
                                    <tr>
                                        <td>'.__('Branchs').'</td>
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
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.merchant.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.merchant.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.merchant.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
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
                __('Logo'),
                __('Name'),
                __('Wallet'),
                __('Counter'),
                __('Action')
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchants');
            }else{
                $this->viewData['pageTitle'] = __('Merchants');
            }

            // Filter Data
            $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);
            $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name','main_category_id']);
            if($MerchantCategory->isNotEmpty()){
                $newCategories = [];
                $mainCategories = $MerchantCategory->where('main_category_id','=','');
                foreach ($mainCategories as $key => $value){
                    $newCategories[$value->id] = $value->name;
                    $subCategories = $MerchantCategory->where('main_category_id','=',$value->id);
                    if($subCategories->isNotEmpty()){
                        foreach ($subCategories as $sKey => $sValue){
                            $newCategories[$sValue->id] = '--- '.$sValue->name;
                        }
                    }
                }
                $this->viewData['merchantCategories'] = ['Select Category']+$newCategories;
            }else{
                $this->viweData['merchantCategories'] = [__('Select Category')];
            }
            // Filter Data

            $merchantPlans =  MerchantPlan::pluck('title','id')->reverse()->put('0',__('Select Group'))->reverse()->toArray();
            $this->viewData['merchantPlans'] = $merchantPlans;


            return $this->view('merchant.merchant.index',$this->viewData);
        }
    }

    public function review(Request $request){

        if($request->isDataTable){

            $eloquentData = TempData::viewData($this->systemLang)
                ->where('type','=','merchant');


            if($request->withTrashed){
                $eloquentData->onlyTrashed();
            }

            whereBetween($eloquentData,'temp_data.created_at',$request->created_at1,$request->created_at2);

            if($request->id){
                $eloquentData->where('create_id', '=',$request->id);
            }



            if(!staffCan('show-tree-users-data')){
                $eloquentData->whereIn('staff_id',Auth::user()->managed_staff_ids());
            }

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('Added by', function($data){
                    $obj = (new $data->create_type)->where('id','=',$data->create_id)->first();
                    switch($data->create_type){
                        case 'App\Models\MerchantStaff':
                            return link_to_route('merchant.staff.show',$obj->name.' ('.$obj->merchant()->{'name_'.$this->systemLang}.')',['id'=>$data->create_id]);
                            break;
                        case 'App\Models\Merchant':
                            return link_to_route('merchant.merchant.show',$obj->{'name_'.$this->systemLang},['id'=>$data->create_id]);
                            break;
                        case 'App\Models\Staff':
                            return link_to_route('system.staff.show',$obj->fullname,['id'=>$data->create_id]);
                            break;
                        default:
                            return 'Unknown';
                            break;
                    }
                })
                ->addColumn('review', function($data){
                    if($data->reviewed_id) {
                        return __('Reviewed by').': '.Staff::where('id',$data->reviewed_id)->first()->fullname.'<br>'.__('At').':'.$data->reviewed_at;
                    } else{
                        return link_to_route('merchant.merchant.create',__('Review'),['tempData'=>$data->id]);
                    }

                })

                ->addColumn('created_at',function($data){
                    return $data->created_at->diffForHumans();
                })
                ->make(true);

        }else{

            // View Data
            $this->viewData['tableColumns'] = [
                __('ID'),
                __('Added by'),
                __('Review'),
                __('Created At'),
            ];

            if($request->withTrashed){
                $this->viewData['pageTitle'] = __('Deleted Merchants');
            }else{
                $this->viewData['pageTitle'] = __('Merchants');
            }

            return $this->view('merchant.merchant.review',$this->viewData);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!staffCan('show-tree-users-data',Auth::id())){
            $getSalesData = Auth::user()->managed_staff;
            if(!$getSalesData){
                $getSalesData = [];
            }else{
                $newGetSalesData = [];
                foreach ($getSalesData as $key => $value){
                    $newGetSalesData[$value->id] = $value->firstname.' '.$value->lastname.' '.__('#ID:').' '.$value->id;
                }
                $getSalesData = $newGetSalesData;
            }
        }else{

            $getSalesData = Staff::select([
                'staff.id',
                'staff.firstname',
                'staff.lastname'
            ])
                ->whereNull('supervisor_id')
                ->get();

            if(!$getSalesData){
                $getSalesData = [];
            }else{
                $newGetSalesData = [];

                $getAllStaff = Staff::whereIn('supervisor_id',array_column($getSalesData->toArray(),'id'))
                    ->get();

                foreach ($getSalesData as $key =>$value){

                    $newKey = $value->firstname.' '.$value->lastname.' '.__('#ID:').' '.$value->id;
                    $newGetSalesData[$newKey] = [];

                    foreach ($getAllStaff->where('supervisor_id',$value->id) as $key2 => $value2){
                        $newGetSalesData[$newKey][$value2->id] = $value2->firstname.' '.$value2->lastname.' '.__('#ID:').' '.$value2->id;
                    }
                }


                $getSalesData = $newGetSalesData;
            }

        }


        $this->viewData['sales_data'] = $getSalesData;



        if(isset($request->tempData)){
            $tmpData = TempData::where('id','=',(int) $request->tempData)
                ->whereNull('reviewed_id')
                ->whereNull('reviewed_at')
                ->where('type','=','merchant')
                //->whereIn('create_id',$managedIDS)
                ->first();
            if($tmpData){
                $tempDataData = $tmpData->data;
                $sMobile = $tempDataData['contact']['mobile'][0];
                $tempDataData['contract_admin_name'] = $tempDataData['contact']['name'][0];
                $tempDataData['contract_admin_job_title'] = 'Owner';
                $tempDataData['contract_price'] = 0;
                try {
                    $date = str_split(substr($tempDataData['staff_national_id'],1,6),2);
                    if($date[0] > date('y'))
                        $date[0] = '19'.$date[0];
                    else
                        $date[0] = '20'.$date[0];
                    $tempDataData['staff_birthdate'] = Carbon::createFromFormat('Y-m-d', implode('-',$date))->format('Y-m-d');

                } catch (\Exception $e){}
                Input::merge(array_merge($tempDataData,['staff_mobile'=>$sMobile]));
                $this->viewData['tempData'] = $tmpData;
            }
        }

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);


        $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name','main_category_id']);
        if($MerchantCategory->isNotEmpty()){
            $newCategories = [];
            $mainCategories = $MerchantCategory->where('main_category_id','=','');
            foreach ($mainCategories as $key => $value){
                $newCategories[$value->id] = $value->name;
                $subCategories = $MerchantCategory->where('main_category_id','=',$value->id);
                if($subCategories->isNotEmpty()){
                    foreach ($subCategories as $sKey => $sValue){
                        $newCategories[$sValue->id] = '--- '.$sValue->name;
                    }
                }
            }
            $this->viewData['merchant_categories'] = ['Select Category']+$newCategories;
        }else{
            $this->viweData['merchant_categories'] = [__('Select Category')];
        }

        $merchantPlans =  MerchantPlan::get(['id','title'])->toArray();
        $merchantPlans = [''=>__('Select Plan')]    +array_column($merchantPlans,'title','id');
        $this->viewData['merchantPlans'] = $merchantPlans;
        $this->viewData['contract_papers'] = explode("\r",setting('contract_papers'));

        return $this->view('merchant.merchant.create',$this->viewData);
    }



    public function fastCreate(Request $request)
    {
        if(!staffCan('show-tree-users-data',Auth::id())){
            $getSalesData = Auth::user()->managed_staff;
            if(!$getSalesData){
                $getSalesData = [];
            }else{
                $newGetSalesData = [];
                foreach ($getSalesData as $key => $value){
                    $newGetSalesData[$value->id] = $value->firstname.' '.$value->lastname.' '.__('#ID:').' '.$value->id;
                }
                $getSalesData = $newGetSalesData;
            }
        }else{

            $getSalesData = Staff::select([
                'staff.id',
                'staff.firstname',
                'staff.lastname'
            ])
                ->whereNull('supervisor_id')
                ->get();

            if(!$getSalesData){
                $getSalesData = [];
            }else{
                $newGetSalesData = [];

                $getAllStaff = Staff::whereIn('supervisor_id',array_column($getSalesData->toArray(),'id'))
                    ->get();

                foreach ($getSalesData as $key =>$value){

                    $newKey = $value->firstname.' '.$value->lastname.' '.__('#ID:').' '.$value->id;
                    $newGetSalesData[$newKey] = [];

                    foreach ($getAllStaff->where('supervisor_id',$value->id) as $key2 => $value2){
                        $newGetSalesData[$newKey][$value2->id] = $value2->firstname.' '.$value2->lastname.' '.__('#ID:').' '.$value2->id;
                    }
                }


                $getSalesData = $newGetSalesData;
            }

        }


        $this->viewData['sales_data'] = $getSalesData;



        if(isset($request->tempData)){
            $tmpData = TempData::where('id','=',(int) $request->tempData)
                ->where('type','=','merchant')
                //->whereIn('create_id',$managedIDS)
                ->first();
            if($tmpData){
                Input::merge($tmpData->data);
            }
        }

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create Merchant'),
        ];

        $this->viewData['pageTitle'] = __('Create Merchant');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);


        $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name','main_category_id']);
        if($MerchantCategory->isNotEmpty()){
            $newCategories = [];
            $mainCategories = $MerchantCategory->where('main_category_id','=','');
            foreach ($mainCategories as $key => $value){
                $newCategories[$value->id] = $value->name;
                $subCategories = $MerchantCategory->where('main_category_id','=',$value->id);
                if($subCategories->isNotEmpty()){
                    foreach ($subCategories as $sKey => $sValue){
                        $newCategories[$sValue->id] = '--- '.$sValue->name;
                    }
                }
            }
            $this->viewData['merchant_categories'] = ['Select Category']+$newCategories;
        }else{
            $this->viweData['merchant_categories'] = [__('Select Category')];
        }

        $merchantPlans =  MerchantPlan::get(['id','title'])->toArray();
        $merchantPlans = [''=>__('Select Plan')]    +array_column($merchantPlans,'title','id');
        $this->viewData['merchantPlans'] = $merchantPlans;
        $this->viewData['contract_papers'] = explode("\r",setting('contract_papers'));

        return $this->view('merchant.merchant.fast-create',$this->viewData);
    }

    public function FastCreateAction(Request $request){
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
        if(count($name) >= 2){
            unset($name[0]);
            $RequestData['staff_lastname']  = implode(' ',$name);
        }else{
            $RequestData['staff_lastname']  = ' -- ';
        }

        $RequestData['staff_email'] = $RequestData['staff_national_id'].'@merchant.egpay.com';

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
            'name_ar'                               => 'required',
            'merchant_category_id'                  => 'numeric',
            'area_id'                               => 'required',
            'contact.name.*'                        => 'required',
            'contact.email.*'                       => 'required|email',
            'contact.mobile.*'                      => 'required|digits:11',

            //Branch validation
            'branch_name_en'                        => 'required',
            'branch_address_en'                     => 'required',
//            'branch_description_en'                 => 'required',
            'branch_name_ar'                        => 'required',
            'branch_address_ar'                     => 'required',
//            'branch_description_ar'                 => 'required',
            'branch_latitude'                       => 'required',
            'branch_longitude'                      => 'required',

            //Employee validation
            'staff_firstname'                    =>  'required',
            'staff_lastname'                     =>  'required',
            'staff_email'                        =>  'required|email|unique:merchant_staff,email',
            'staff_national_id'                  =>  'required|digits:14',
            'contractTitle.*'                    =>  'required',
            'contractFile.*'                     =>  'required|image',
        ])->validate();


        $theRequest = $RequestData;
        $theRequest['area_id'] = getLastNotEmptyItem($RequestData['area_id']);
        unset($theRequest['contractTitle'],$theRequest['contractFile']);

        $insert = TempData::create([
            'type'          =>      'merchant',
            'data'          =>      $theRequest,
            'create_id'     =>      Auth::id(),
            'create_type'   =>      get_class(Auth::user()),
        ]);

        if($insert){
            if($request->file('contractFile')) {
                $ContractNames = explode("\r", setting('contract_papers'));
                foreach($request->file('contractFile') as $key => $val){
                    $uploads[$key]['path'] = str_replace('public/','',$val->store('public/temp/contract'));
                    $uploads[$key]['title'] = $ContractNames[$RequestData['contractTitle'][$key]];
                }
                $insert->uploads()->createMany($uploads);
            }
            return redirect()->route('merchant.merchant.fast-create')
                ->with('status', 'success')
                ->with('msg', __('Merchant successfully added to be reviewed'));
        } else {
            return redirect()->route('merchant.merchant.fast-create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add Merchant'));
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MerchantFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('merchant/'.date('y').'/'.date('m'));
        }else{
            $theRequest['logo'] = null;
        }
        $theRequest['area_id'] = getLastNotEmptyItem($request->area_id);
        $alreadyUploadedContractFiles = [];
        if(isset($request->old_contract_title)){
            foreach($request->old_contract_title as $key=>$val){
                $alreadyUploadedContractFiles[$key]['title'] = $val;
                $alreadyUploadedContractFiles[$key]['path'] = $theRequest['old_contract_file'][$key];
            }
        }


        if(isset($request->imagetitles) && isset($request->imagefiles)){
            $merchantImages = array_combine($request->imagetitles,$request->imagefiles);
        }

        if(!$request->parent_id){
            $theRequest['parent_id'] = null;
        }

        if($theRequest['temp_data']){
            $theRequest['staff_password'] = rand(100000,999999);
            $SMS = new SMS();
            //$SMS->Send($theRequest['staff_mobile'],__('Your account has been successfuly created || your password is').':'.$theRequest['staff_password']);
        }
        $theRequest['staff_id'] = Auth::id();


        $create = Create::Merchant(
        // Merchant
            [
                'is_reseller'=> $theRequest['is_reseller'],
                'area_id'=> $theRequest['area_id'],
                'name_ar'=> $theRequest['name_ar'],
                'name_en'=> $theRequest['name_en'],
                'description_ar'=> $theRequest['description_ar'],
                'description_en'=> $theRequest['description_en'],
                'address'=> $theRequest['address'],
                'logo'=> $theRequest['logo'],
                'merchant_category_id'=> $theRequest['merchant_category_id'],
                'status'=> $theRequest['status'],
                'staff_id'=> $theRequest['staff_id'],
                'parent_id'=> $theRequest['parent_id']
            ],

            // Contact
            $theRequest['contact'],

            // Branch
            [
                'name_ar'=> $theRequest['branch_name_ar'],
                'name_en'=> $theRequest['branch_name_ar'],
                'address_ar'=> $theRequest['branch_address_ar'],
                'address_en'=> $theRequest['branch_address_en'],
                'description_ar'=> $theRequest['branch_description_ar'],
                'description_en'=> $theRequest['branch_description_en'],
                'latitude'=> $theRequest['branch_latitude'],
                'longitude'=> $theRequest['branch_longitude']
            ],

            // Staff
            [
                'firstname' => $theRequest['staff_firstname'],
                'lastname'  => $theRequest['staff_lastname'],
                'username'  => $theRequest['staff_username'] ?? null,
                'national_id'=> $theRequest['staff_national_id'],
                'address'=> $theRequest['staff_address'],
                'birthdate'=> $theRequest['staff_birthdate'],
                'email'=> $theRequest['staff_email'],
                'password'=> bcrypt($theRequest['staff_password']),
                'mobile'=> $theRequest['staff_mobile']
            ],

            // Contract
            [
                'plan_id'=> $theRequest['contract_plan_id'],
                'description'=> $theRequest['contract_description'],
                'price'=> $theRequest['contract_price'],
                'admin_name'=> $theRequest['contract_admin_name'],
                'admin_job_title'=> $theRequest['contract_admin_job_title']
            ],

            // Contract Files
            @$theRequest['file'],

            // Contract File's title
            @$theRequest['title'],

            $alreadyUploadedContractFiles,

            @$theRequest['temp_data'],
            @$merchantImages

        );

        if(!$create) {
            return redirect()
                ->route('merchant.merchant.create')
                ->with('status','danger')
                ->with('msg',__('Sorry couldn\'t add Merchant'));
        }else{
            return redirect()
                ->route('merchant.merchant.show',$create->id)
                ->with('status','success')
                ->with('msg',__('Merchant Data has been added successfully'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */

    public function show(Merchant $merchant,Request $request){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }


        if($request->isBranches){

            $eloquentData = \App\Models\MerchantBranch::viewData($this->systemLang)
                ->where('merchant_branches.merchant_id',$merchant->id);

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


        }elseif($request->isContract){
            $eloquentData = \App\Models\MerchantContract::viewData($this->systemLang)
                ->where('merchant_id',$merchant->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','<a target="_blank" href="{{route(\'merchant.contract.show\',$id)}}">{{$id}}</a>')
                ->addColumn('description','{{str_limit($description,10)}}')
                ->addColumn('price','{{$price}} {{__(\'LE\')}}')
                ->addColumn('plan_title','<a target="_blank" href="{{route(\'merchant.plan.show\',$plan_id)}}">{{$plan_title}}</a>')
                ->addColumn('start_date','{{$start_date}}')
                ->addColumn('end_date','{{$end_date}}')
                ->addColumn('staff_firstname',function($data){
                    return '<a target="_blank" href="'.route('system.staff.show',$data->staff_id).'">'.$data->staff_firstname.' '.$data->staff_lastname.'</a>';
                })
                ->make(true);
        }elseif($request->isProductCategory){
            $eloquentData = \App\Models\MerchantCategory::viewData($this->systemLang)
                ->where('merchant_id',$merchant->id);

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
                ->addColumn('action',function($data){
                    return " <div class=\"dropdown\">
                              <button class=\"btn btn-primary dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\"><i class=\"ft-cog icon-left\"></i>
                              <span class=\"caret\"></span></button>
                              <ul class=\"dropdown-menu\">
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.category.show',$data->id)."\">".__('View')."</a></li>
                                <li class=\"dropdown-item\"><a href=\"".route('merchant.category.edit',$data->id)."\">".__('Edit')."</a></li>
                                <li class=\"dropdown-item\"><a onclick=\"deleteRecord('".route('merchant.category.destroy',$data->id)."')\" href=\"javascript:void(0)\">".__('Delete')."</a></li>
                              </ul>
                            </div>";
                })
                ->make(true);
        }elseif($request->isStaff){
            $eloquentData = \App\Models\MerchantStaff::viewData($this->systemLang)
                ->where('merchant_staff_group.merchant_id',$merchant->id);

            return Datatables::eloquent($eloquentData)
                ->addColumn('id','{{$id}}')
                ->addColumn('name','{{$username}}')
                ->addColumn('firstname','{{$firstname}} {{$lastname}}')
                ->addColumn('merchant_staff_group_title','{{$merchant_staff_group_title}}')
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
        }else{

            $this->viewData['MerchantStaffGroup'] = \App\Models\MerchantStaffGroup::where('merchant_id',$merchant->id)->get();

            $this->viewData['breadcrumb'] = [
                [
                    'text'=> __('Home'),
                    'url'=> url('system'),
                ],
                [
                    'text'=> __('Merchant Category'),
                    'url'=> url('system/merchant-category'),
                ],
                [
                    'text'=> __('Merchant Category'),
                ]
            ];

            $this->viewData['pageTitle'] = __('Merchant Category');


            // -- Products
            $this->viewData['productsCategories'] = $merchant->productCategories;
            $this->viewData['products'] = [];

            $merchantProducts = $merchant->products;
            if($merchantProducts){
                foreach ($merchantProducts as $key => $value){
                    $this->viewData['products'][$value->merchant_product_category_id][] = $value;
                }
            }
            // -- Products

            // -- Staff
            $this->viewData['merchantStaffGroups'] = [];
            $this->viewData['merchantStaff'] = [];

            $MerchantStaffGroup = $merchant->MerchantStaffGroup;

            if($MerchantStaffGroup->isNotEmpty()){
                $this->viewData['merchantStaffGroups'] = $MerchantStaffGroup;
                $MerchantStaffGroupIDs = array_column($MerchantStaffGroup->toArray(),'id');
                $merchantStaff = MerchantStaff::whereIn('merchant_staff_group_id',$MerchantStaffGroupIDs)->get();

                foreach ($merchantStaff as $key => $value){
                    $this->viewData['merchantStaff'][$value->merchant_staff_group_id][] = $value;
                }
            }

            // -- Staff
            $this->viewData['result'] = $merchant;
            return $this->view('merchant.merchant.show',$this->viewData);

        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(Merchant $merchant)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit Merchant'),
        ];

        $this->viewData['pageTitle'] = __('Edit Merchant');
        $this->viewData['areaData'] = AreaType::getFirstArea($this->systemLang);

        $MerchantCategory = MerchantCategory::get(['id','name_'.$this->systemLang.' as name','main_category_id']);
        if($MerchantCategory->isNotEmpty()){
            $newCategories = [];
            $mainCategories = $MerchantCategory->where('main_category_id','=','');
            foreach ($mainCategories as $key => $value){
                $newCategories[$value->id] = $value->name;
                $subCategories = $MerchantCategory->where('main_category_id','=',$value->id);
                if($subCategories->isNotEmpty()){
                    foreach ($subCategories as $sKey => $sValue){
                        $newCategories[$sValue->id] = '--- '.$sValue->name;
                    }
                }
            }
            $this->viewData['merchant_categories'] = ['Select Category']+$newCategories;
        }else{
            $this->viweData['merchant_categories'] = [__('Select Category')];
        }

        $this->viewData['result'] = $merchant;
        $this->viewData['contract_papers'] = explode("\r",setting('contract_papers'));
        return $this->view('merchant.merchant.create',$this->viewData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Merchant $merchant,MerchantFormRequest $request)
    {
        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        $theRequest = $request->all();
        if($request->file('logo')) {
            $theRequest['logo'] = $request->logo->store('merchant/'.date('y').'/'.date('m'));
        }

        $theRequest['area_id'] = getLastNotEmptyItem($request->area_id);

        if($merchant->update($theRequest)) {
            if($request->contact){
                foreach ($merchant->contact()->get() as $contact) {
                    $contact->delete();
                }
                $contactInfo = new Collection();
                foreach ($request->contact as $key => $value) {
                    foreach ($value as $contact) {
                        $contactInfo->push(new Contacts(['model_id' => $merchant->id, 'type' => $key, 'value' => $contact]));
                    }
                }
                $merchant->contact()->saveMany($contactInfo);
            }

            return redirect()
                ->route('merchant.merchant.edit',$merchant->id)
                ->with('status','success')
                ->with('msg',__('Successfully edited Merchant'));
        }else{
            return redirect()
                ->route('merchant.merchant.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit Merchant'));

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Merchant $merchant,Request $request){

        if(!staffCan('show-tree-users-data',Auth::id()) && !in_array($merchant->staff_id,Auth::user()->managed_staff_ids())){
            abort(404);
        }

        // Delete Data
        $merchant->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('Merchant has been deleted successfully')];
        }else{
            redirect()
                ->route('merchant.index')
                ->with('status','success')
                ->with('msg',__('This Merchant has been deleted'));
        }
    }


}