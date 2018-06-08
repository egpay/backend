<?php

namespace App\Modules\System;

use App\Libs\WalletData;
use App\Models\Advertisement;
use App\Models\MerchantBranch;
use App\Models\MerchantCategory;
use App\Models\MerchantProduct;
use App\Models\MerchantStaffGroup;
use App\Models\PaymentSDKGroup;
use App\Models\PaymentServiceProviderCategories;
use App\Models\PaymentServiceProviders;
use App\Models\PaymentServices;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Merchant;
use App\Models\AreaType;
use App\Models\Staff;
use App\Models\User;
use App\Models\MerchantPlan;
use App\Models\MerchantProductCategory;
use App\Models\MerchantStaff;
use Carbon;
use App\Models\PaymentInvoice;
use App\Models\PermissionGroup;

class AjaxController extends SystemController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function get(Request $request){

        switch ($request->type){


            case 'getStaffWallets':
                $id = $request->id;
                $staff = Staff::findOrFail($id);

                $newWallet = [];
                foreach ($staff->wallet as $key => $value){
                    $newWallet[$value->id] = ucfirst($value->type).' ('.amount(WalletData::balance($value),true).')';
                }

                return $newWallet;

                break;

            case 'getMerchantWallets':
                $id = $request->id;
                $staff = Merchant::findOrFail($id);

                $newWallet = [];
                foreach ($staff->wallet as $key => $value){
                    $newWallet[$value->id] = ucfirst($value->type).' ('.amount(WalletData::balance($value),true).')';
                }

                return $newWallet;

                break;

            case 'system_load_avg':
                if(function_exists('sys_getloadavg')){
                    return sys_getloadavg()[0];
                }

                return 0.5;

                break;

            case 'getTransaction':
                $id       = $request->id;
                $ownerMD5 = $request->ownerMD5;

                $data = WalletTransaction::with(['model','fromWallet','toWallet','transactions_status'])->find($id);
                if(!$data){
                    return ['status'=> false];
                }
                $result = $data->toArray();
                if($data->fromWallet == null){
                    $result['fromType'] = ' -- ';
                    $result['fromName'] = ' -- ';
                }else{
                    $result['fromType'] = WalletData::getWalletOwnerType($data->fromWallet->walletowner_type);
                    if($ownerMD5 == md5($data->fromWallet->walletowner_id.$data->fromWallet->walletowner_type)){
                        $result['fromName'] = '<span style="color: red;">'.getWalletOwnerName($data->fromWallet,$this->systemLang).'</span>';
                    }else{
                        $result['fromName'] = '<a target="_blank" href="'.route('system.wallet.show',['ID'=> $data->fromWallet->id]).'">'.getWalletOwnerName($data->fromWallet,$this->systemLang).'</a>';
                    }
                }
                if($data->toWallet == null){
                    $result['toType'] = ' -- ';
                    $result['toName'] = ' -- ';
                }else{
                    $result['toType'] = WalletData::getWalletOwnerType($data->toWallet->walletowner_type);
                    if($ownerMD5 == md5($data->toWallet->walletowner_id.$data->toWallet->walletowner_type)){
                        $result['toName'] = '<span style="color: red;">'.getWalletOwnerName($data->toWallet,$this->systemLang).'</span>';
                    }else{
                        $result['toName'] = '<a target="_blank" href="'.route('system.wallet.show',['ID'=> $data->toWallet->id]).'">'.getWalletOwnerName($data->toWallet,$this->systemLang).'</a>';
                    }
                }

                $result['modelName'] = WalletData::getModelTypeByModel($data->model_type);
                $result['updated_at'] = $data->updated_at->diffForHumans();

                if($result['modelName'] == 'invoice'){
                    $result['payment_services_name'] = $data->model_data->payment_services->{'name_'.$this->systemLang};
                }else{
                    // @TODO: order
                    //$result['payment_services_name'] = $data->model->payment_services->{'name_'.$this->systemLang};
                }


                return ['status'=> true,'data'=>$result];

                break;

            case 'getSDKGroup':
                $id = $request->id;
                return PaymentSDKGroup::where('payment_sdk_id',$id)->get(['id','name'])->toJson();

                break;

            case 'getAdvertisementAnalytics':
                $advertisement_id = $request->advertisement_id;
                $year = $request->year;
                $month = $request->month;
                $day = $request->day;
                if(!$year){
                    $year = date('Y');
                }

                $advertisement = Advertisement::where('id',$advertisement_id)->first();

                if(!$advertisement){
                    return ['status'=> false];
                }


                // -- ADV Analytics
                $analytics = collect($advertisement->analytics($advertisement->id,$year,$month,$day))->map(function($data){
                    $date = explode('-',explode(' ',$data->user_action_date_time)[0]);
                    $data->yearMonth = $date[0].'-'.$date[1];
                    $data->month = (int)$date[1];
                    return $data;
                });

                $clickAnalytics = collect($analytics)->where('user_action_type','=','click')->count();
                $viewAnalytics  = collect($analytics)->where('user_action_type','=','view')->count();

                // Gender
                $maleAnalytics   = collect($analytics)->where('gender','=','male')->count();
                $femaleAnalytics = collect($analytics)->where('gender','=','female')->count();
                $nullAnalytics   = collect($analytics)->where('gender','=',null)->count();

                $groupByDateClickAnalytics = collect($analytics)
                    ->where('user_action_type','=','click')
                    ->groupBy('month')
                    ->toArray();

                // ----
                $groupByDateViewAnalytics = collect($analytics)
                    ->where('user_action_type','=','view')
                    ->groupBy('month')
                    ->toArray();


                $monthsClick = [];
                if(!empty($groupByDateClickAnalytics)){
                    for($i=1;$i<=12;$i++){
                        if(isset($groupByDateClickAnalytics[$i])){
                            $monthsClick[$i] = count($groupByDateClickAnalytics[$i]);
                        }else{
                            $monthsClick[$i] = 0;
                        }
                    }
                }


                $monthsView = [];
                if(!empty($groupByDateViewAnalytics)){
                    for($i=1;$i<=12;$i++){
                        if(isset($groupByDateViewAnalytics[$i])){
                            $monthsView[$i] = count($groupByDateViewAnalytics[$i]);
                        }else{
                            $monthsView[$i] = 0;
                        }
                    }
                }
                // -- ADV Analytics

                return [
                    'status'=> true,
                    'year'=> $year,
                    'monthsView'=> $monthsView,
                    'monthsClick'=> $monthsClick,
                    'clickAnalytics'=> $clickAnalytics,
                    'viewAnalytics'=> $viewAnalytics,
                    'maleAnalytics'=> $maleAnalytics,
                    'femaleAnalytics'=> $femaleAnalytics,
                    'nullAnalytics'=> $nullAnalytics
                ];

                break;


            case 'getProductCategory':
                $merchantID = $request->merchant_id;
                $Categories = MerchantProductCategory::viewData($this->systemLang)
                    ->where('merchant_product_categories.merchant_id',$merchantID)->get()->toArray();
                array_unshift($Categories,['id'=>'0','name'=>__('Select product category')]);
                return $Categories;

                break;

            case 'getProducts':
                $catID = (int) $request->category_id;
                $Products = MerchantProduct::select(['id',"merchant_products.name_{$this->systemLang} as name",'price'])
                    ->where('merchant_product_category_id',$catID)->get()->toArray();
                array_unshift($Products,['id'=>'0','name'=>__('Select product')]);
                return $Products;

            break;

            case 'getMerchantStaff':
                $merchantID = $request->merchant_id;

                return MerchantStaff::viewData($this->systemLang)
                    ->where('merchant_staff_groups.merchant_id',$merchantID)->get();

                break;

            case 'getMerchantBranches':
                $merchantID = $request->merchant_id;

                return MerchantBranch::viewData($this->systemLang)
                    ->where('merchant_branches.merchant_id',$merchantID)->get();

                break;


            case 'getMerchantStaffGroup':
                $merchantID = $request->merchant_id;

                return MerchantStaffGroup::where('merchant_id',$merchantID)->get();

                break;


            case 'merchantNewContractsDates':

                $merchantID = $request->merchant_id;
                $planID     = $request->plan_id;


                $merchant        = Merchant::getWithRelations($merchantID);
                $merchantNewPlan = MerchantPlan::find($planID);

                if(!$merchantNewPlan){
                    return ['status'=> false];
                }elseif($merchant && $merchant->end_date){
                    $start_date = $merchant->end_date;
                    $explodeEndData = explode('-',$merchant->end_date);
                    $end_date = (Carbon::create($explodeEndData[0],$explodeEndData[1],$explodeEndData[2]))
                        ->addMonths($merchantNewPlan->months);
                }else{
                    $start_date = (string) Carbon::now()->format('Y-m-d');
                    $end_date = (Carbon::now())
                        ->addMonths($merchantNewPlan->months);
                }

                $end_date = (string) $end_date;
                return ['stauts'=> true,'start_date'=> $start_date,'end_date'=> explode(' ',$end_date)[0]];

                break;
            // MOSTAFA
            // finduser
            case 'user':
                $word = $request->word;
                $data = User::where('id','=',$word)
                    ->orwhere('firstname','LIKE','%'.$word.'%')
                    ->orwhere('lastname','LIKE','%'.$word.'%')
                    ->orwhere('email','LIKE','%'.$word.'%')
                    ->orwhere('mobile','LIKE','%'.$word.'%')
                    ->orwhere('national_id','=',$word)
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;
                break;

            // MOSTAFA


            case 'getNextAreas':
                return \App\Libs\AreasData::getNextAreas($request->id,$this->systemLang);
                break;

            case 'staff':
                $word = $request->word;
                $data = Staff::where('firstname','LIKE','%'.$word.'%')
                    ->orWhere('lastname','LIKE','%'.$word.'%')
                    ->orWhere('email','LIKE','%'.$word.'%')
                    ->orWhere('mobile','LIKE','%'.$word.'%')
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;
                break;


            case 'managed_staff':
                $word = $request->word;
                $data = Staff::join('permission_groups','permission_groups.id','staff.permission_group_id')
                    ->where('permission_groups.is_supervisor','=','no')
                    ->whereRaw('staff.supervisor_id is null')
                    ->where(function($query) use($word){
                        $query->where('firstname','LIKE','%'.$word.'%')
                            ->orWhere('lastname','LIKE','%'.$word.'%')
                            ->orWhere('email','LIKE','%'.$word.'%')
                            ->orWhere('mobile','LIKE','%'.$word.'%');
                    })
                    ->get([
                        'staff.id',
                        'staff.firstname',
                        'staff.lastname'
                    ]);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;
                break;





            case 'merchant':
                $word = $request->word;
                $data = Merchant::where('name_ar','LIKE',"%$word%")
                    ->orWhere('name_en','LIKE',"%$word%")
                    ->orWhere('description_ar','LIKE',"%$word%")
                    ->orWhere('description_en','LIKE',"%$word%")
                    ->orWhere('id',$word)
                    ->get(['id',"name_{$this->systemLang} as name"]);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id
                    ];
                }

                return $return;

                break;

            case 'paymentinvoice':
                $word = $request->word;
                $data = PaymentInvoice::Where('payment_invoice.id',$word)

                    ->join('payment_transactions','payment_invoice.payment_transaction_id','=','payment_transactions.id')

                    ->join('payment_services','payment_services.id','=','payment_transactions.payment_services_id')

                    ->join('payment_service_providers','payment_service_providers.id','=','payment_services.payment_service_provider_id')
                    ->join('payment_service_provider_categories','payment_service_provider_categories.id','=','payment_service_providers.payment_service_provider_category_id')
                    ->get([
                        'payment_invoice.id',
                        \DB::raw("CONCAT('#',payment_invoice.id,' ',payment_services.name_{$this->systemLang},' (',payment_service_providers.name_{$this->systemLang},')') as name"),
                        //"payment_services.name_{$this->systemLang} as name"
                    ]);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id
                    ];
                }

                return $return;

            break;


            /*
             * Call center forward to group
             */
            case 'forward-to-group':
            $word = $request->word;
            $data = PermissionGroup::where('id',$word)
                ->orWhere('name','LIKE','%'.$word.'%')
                ->get(['id','name AS value']);
            return $data;
            break;

            /*
             * Call center forward to staff
             */
            case 'forward-to-staff':
            $word = $request->word;
            $groupid = $request->groupid;
                if($groupid>0)
                    $data = Staff::where('permission_group_id','=',$groupid);
                else
                    $data = Staff::whereNotNull('permission_group_id');
                $data->where(function($query)use($word){
                    $query->where('id',$word)
                        ->orWhere('firstname','LIKE','%'.$word.'%')
                        ->orWhere('lastname','LIKE','%'.$word.'%');
                });
                return $data->get(['id',\DB::RAW("CONCAT(`firstname`,' ',`lastname`,' ID#',`id`) as value")]);
            break;



            case 'users':
                $word = $request->word;
                $data = User::whereRaw("CONCAT(firstname,' ',lastname) LIKE('%?%')",[$word])
                    ->orWhere('email','LIKE',"%$word%")
                    ->orWhere('mobile','LIKE',"%$word%")
                    ->orWhere('national_id','LIKE',"%$word%")
                    ->orWhere('id',$word)
                    ->get(['id','firstname','lastname']);

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->firstname.' '.$value->lastname.' #ID:'.$value->id
                    ];
                }

                return $return;

                break;


            case 'getMerchantBranchs':
                $merchantID = $request->merchant_id;

                return MerchantBranch::where('merchant_id',$merchantID)
                    ->select('id','name_'.$this->systemLang.' as name')->get();

                break;


            case 'product':
                $catid = (int) $request->catid;

                $data = MerchantProduct::select(['id',"merchant_products.name_{$this->systemLang} as name",'price'])
                    ->where('merchant_product_category_id','=',$catid)
                    ->withCount('attribute')
                    ->Active()
                    ->get();

                $return = [];
                foreach ($data as $value) {
                    $return[] = [
                        'id'=> $value->id,
                        'value'=>  $value->name.' #ID:'.$value->id,
                        'price'=>   $value->product_price(),
                        'attribute'=>$value->attribute_count
                    ];
                }

                return $return;

                break;

            case 'productAttributes':
                $proid = (int) $request->proid;
                $lang = $this->systemLang;
                $attributes = ProductAttribute::viewData($this->systemLang,[])->where('product_id','=',$proid)->get();

                $oldattributevalues = Attribute::whereIn('id',$attributes->pluck('attribute_id'))
                    ->with(['attributeValue'=>function($sqlQuery)use($lang){
                        $sqlQuery->select(['id','attribute_id','text_'.$lang.' as text','is_default']);
                    }])->get();

                return $attributes->groupBy('attribute_id');

                break;

                case 'customer':
                    $word = $request->word;
                    if(strlen($word < 11))
                        return;
                    $data = User::where('mobile','=',$word)
                        ->orwhere('mobile','LIKE','%'.$word.'%')
                        ->get(['id','mobile as value']);

                    return $data;

                    $return = [];
                    foreach ($data as $value) {
                        $return[] = [
                            'id'=> $value->id,
                            'value'=>  $value->mobile.' #ID:'.$value->id
                        ];
                    }

                    return $return;
                break;


            case 'payment_service_categories':
                return PaymentServiceProviderCategories::where('status','=','active')
                    ->pluck('name_'.$this->systemLang,'id');
            break;

            case 'payment_service_providers':
                $categoryID = (int) $_GET['category_id'];
                return PaymentServiceProviders::where('status','=','active')
                    ->where('payment_service_provider_category_id','=',$categoryID)
                    ->pluck('name_'.$this->systemLang,'id');
            break;

            case 'payment_services':
                $providerID = (int) $_GET['provider_id'];
                return PaymentServices::where('status','=','active')
                    ->where('payment_service_provider_id','=',$providerID)
                    ->pluck('name_'.$this->systemLang,'id');
            break;

        }

    }


    public function post(Request $request){
        switch ($request->type){
            case '':

                break;


            case 'getLanguage':
                $keys = $request->Langkeys;
                $newkeys = array_column(array_map(function($val){
                    return ['key'=>$val,'value'=>__($val)];
                },$keys),'value','key');
                return response()->json(array_merge($newkeys,['langCode'=>$this->systemLang]));
                break;
        }
    }

}
