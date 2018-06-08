<?php

namespace App\Modules\System;

use App\Libs\Payments\Payments;
use App\Libs\WalletData;
use App\Models\News;
use App\Models\PaymentSDKGroup;
use App\Models\PaymentSDKGroupParameter;
use App\Models\PaymentServiceAPIParameters;
use App\Models\PaymentServiceAPIs;
use App\Models\PaymentServiceProviderCategories;
use App\Models\PaymentServiceProviders;
use App\Models\PaymentServices;
use App\Models\PaymentTransactions;
use App\Models\Staff;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Notifications\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use League\Flysystem\Config;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Gate;
use Form;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\UserFormRequest;
use Validator;
use Carbon;

class BeeController extends SystemController{




    public function list(){

        $payment = Payments::selectAdapterByID(1);
        $data = $payment::rebuildDataBase();
        print_r($data);

        exit;
    }


    public function walletTransactions(Request $request){
        $eloquentData = Auth::user()->wallet->allTransaction();

        whereBetween($eloquentData,'created_at',$request->created_at1,$request->created_at2);
        if($request->status){
            $eloquentData->where('status',$request->status);
        }

        $eloquentData->whereNull('model_id');


        $data = $eloquentData->paginate(10)->toArray();

        $data['items'] = [];
        foreach ($data['data'] as $key => $value){
            $data['items'][] = [
                'id'=> $value['id'],
                'status'=> $value['status'],
                'amount'=> $value['amount']." ج.م",
                'created_at'=> '1 day ago',
                'form_type'=> WalletData::getModelTypeByModel($value['from_wallet']['walletowner_type']),
                'form_id'=> WalletData::getModelTypeByModel($value['from_wallet']['walletowner_id']),
                'form_name'=> 'Amr Alaa',
                'to_type'=> WalletData::getModelTypeByModel($value['to_wallet']['walletowner_type']),
                'to_id'=> WalletData::getModelTypeByModel($value['to_wallet']['walletowner_id']),
                'to_name'=> 'Islam Rady'
            ];
        }

        $data['next_page_url'] = str_replace('http://192.168.1.7','', $data['next_page_url']);

        unset($data['data']);

        return [
            'status'=> true,
            'msg'=> 'done',
            'code'=> 100,
            'data'=> $data
        ];


    }

    public function invoice(Request $request){
        $eloquentData = Auth::user()->payment_invoice()->with(['payment_transaction'=>function($query){
            $query->with(['payment_services'=>function($query){
                $query->select([
                    'id',
                    'name_'.$this->systemLang.' as name',
                    'icon',
                    'payment_service_provider_id'
                ]);

                $query->with(['payment_service_provider'=>function($query){
                    $query->select([
                        'id',
                        'name_'.$this->systemLang.' as name',
                        'logo'
                    ]);
                }]);

            }]);
            $query->select([
                'id',
                'payment_services_id'
            ]);
        }])
            ->join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->select([
                'payment_invoice.id',
                'payment_invoice.payment_transaction_id',
                'payment_invoice.total',
                'payment_invoice.total_amount',
                'payment_invoice.status',
                'payment_invoice.created_at'
            ])
            ->orderBy('id','DESC');

        whereBetween($eloquentData,'payment_invoice.created_at',$request->created_at1,$request->created_at2);

        if($request->invoice_id){
            $eloquentData->where('payment_invoice.id','=',$request->invoice_id3);
        }

        if($request->payment_transaction_id){
            $eloquentData->where('payment_invoice.payment_transaction_id','=',$request->payment_transaction_id);
        }

        if($request->payment_services_id){
            $eloquentData->where('payment_transactions.payment_services_id','=',$request->payment_services_id);
        }

        if($request->status){
            $eloquentData->where('payment_invoice.status','=',$request->status);
        }

//        whereBetween($eloquentData,'payment_transactions.amount',$request->amount1,$request->amount2);
//        whereBetween($eloquentData,'payment_transactions.total_amount',$request->total_amount1,$request->total_amount2);

        $data = $eloquentData->paginate(10)
            ->toArray();

        $data['next_page_url'] = str_replace('http://192.168.1.7','', $data['next_page_url']);

        $data['items'] = [];
        foreach ($data['data'] as $key => $value){
            $data['items'][] = [
                'id'=> $value['id'],
                'payment_transaction_id'=> $value['payment_transaction_id'],
                'total'=> (string) $value['total'],
                'total_amount'=> (string) $value['total_amount'].' ج.م',
                'status'=> $value['status'],
                'created_at'=> 'about 1 year ago',
                'payment_services_id'=> $value['payment_transaction']['payment_services_id'],
                'payment_services_name'=> $value['payment_transaction']['payment_services']['name'],
                'payment_services_icon'=> $value['payment_transaction']['payment_services']['icon'],
                'payment_service_provider_id'=> $value['payment_transaction']['payment_services']['payment_service_provider']['id'],
                'payment_service_provider_name'=> $value['payment_transaction']['payment_services']['payment_service_provider']['name'],
                'payment_service_provider_logo'=> $value['payment_transaction']['payment_services']['payment_service_provider']['logo']
            ];
        }

        unset($data['data']);

        return [
            'status'=> true,
            'msg'=> 'done',
            'code'=> 100,
            'data'=> (object) $data
        ];

    }

    public function getUserServiceByTransaction(){
        $data = Auth::user()
            ->payment_invoice()
            ->join('payment_transactions','payment_transactions.id','=','payment_invoice.payment_transaction_id')
            ->join('payment_services','payment_services.id','=','payment_transactions.payment_services_id')
            ->join('payment_service_providers','payment_service_providers.id','=','payment_services.payment_service_provider_id')
            ->select([
                'payment_services.id',
                \DB::raw('CONCAT(payment_service_providers.name_'.$this->systemLang.'," - ",payment_services.name_'.$this->systemLang.') as name'),
            ])
            ->groupBy('payment_services.id')
            ->orderByRaw('count(payment_services.id) DESC')
            ->get();

        return [
            'status'=> true,
            'msg'=> 'done',
            'code'=> 100,
            'data'=> $data->toArray()
        ];
    }

    public function allNews(){
        $result = News::with('category')
            ->select([
                'id',
                'name_ar as name',
                'content_ar as content',
                'image',
                'created_at'
            ])
            ->paginate(10)->withPath('http://192.168.1.7/egpay/public/system/payment-api/allNews')->toArray();

        if(!$result['next_page_url']){
            $result['next_page_url'] = "";
        }

        $result['items'] = $result['data'];
        unset($result['data']);

        return [
            'status'=> true,
            'code'=> 100,
            'msg'=>'Done',
            'data'=> $result
        ];
    }

    public function getDatabase(){
        sleep(10);
/*
        echo serialize(
            [
                [
                    'key'=> 'pin_code',
                    'language'=>[
                        'ar'=> 'رقم الكارت',
                        'en'=> 'Card Number'
                    ]
                ],
                [
                    'key'=> 'serial_number',
                    'language'=>[
                        'ar'=> 'سيريال كارت الشحن',
                        'en'=> 'Serial Number'
                    ]
                ],

            ]
        );
exit;*/
    //    sleep(10);

        $returnData = new \stdClass();
        $returnData->status = true;
        $returnData->code   = 100;
        $returnData->msg    = 'Success';


        // ------- START DATA
        $data = new \stdClass();
        $data->service_provider_categories = PaymentServiceProviderCategories::where('status','active')
        ->get(['id','name_ar','name_en','description_ar','description_en','icon'])->toArray();

        $data->service_providers = PaymentServiceProviders::where('status','active')
            ->get([
                'id',
                'payment_service_provider_category_id as service_provider_category_id',
                'name_ar',
                'name_en',
                'description_ar',
                'description_en',
                'logo'
            ])->toArray();

        $services = PaymentServices::where('status','active')
            ->with(['payment_service_apis'=> function($data){
                $data->with('payment_service_api_parameters');
            }])
            ->get([
                'id',
                'payment_service_provider_id as service_provider_id',
                'name_ar',
                'name_en',
                'description_ar',
                'description_en',
                'icon'
            ]);


        $data->services = [];

        foreach ($services->toArray() as $key => $value){
            $data->services[$key] = $value;
            unset($data->services[$key]['payment_service_apis']);
        }

        $payment_service_apis = [];
        foreach ($services as $key => $value){

            foreach ($value->payment_service_apis as $VK => $VV){

                if($VV->payment_service_api_parameters->isEmpty()){
                    break;
                }

                foreach ($VV->payment_service_api_parameters as $BB){
                        $payment_service_apis[] = [
                            'id'=> $BB->id,
                            'service_type'=> $VV->service_type,
                            'external_system_id'=> $BB->external_system_id,
                            'payment_service_id'=> $value->id,
                            'name_ar'=> $BB->name_ar,
                            'name_en'=> $BB->name_en,
                            'position'=> $BB->position,
                            'visible'=> $BB->visible,
                            'required'=> $BB->required,
                            'type'=> $BB->type,
                            'is_client_id'=> $BB->is_client_id,
                            'default_value'=> $BB->default_value,
                            'min_length'=> $BB->min_length,
                            'max_length'=> $BB->max_length
                        ];
                }

            }

        }

        $data->service_parameters = $payment_service_apis;

        $data->options = [
            [
                'name'=> 'last_update',
                'value'=> '2017-11-11 12:11:11'
            ]
        ];
        // -------- END DATA

        $returnData->data = $data;



        $outPut = str_replace([':null}',':null,'],[':""}',':"",'],json_encode($returnData));
         file_put_contents('payment.json',$outPut);
         echo $outPut;
         exit;

    }

    public function inquiry(Request $request){


        $adapter = Payments::selectAdapterByService(8);
        print_r($adapter::inquiry(64,[
            'parameter_1294'=> '0237800562'
        ]));

        return;

        $inputs = $request->only([
            'service_id',
            'parameters'
        ]);


        if(!$inputs['parameters']){
            $inputs['parameters'] = [];
        }

        $validator = Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
            'parameters'=> 'array'
        ]);

        if ($validator->fails()){
            return [
                'status'=> false,
                'msg'=> __('Validation Error'),
                'code'=> 103,
                'data'=> $validator->errors()
            ];
        }

        $inputs['parameters'] = array_column($inputs['parameters'],1,0);

        $adapter = Payments::selectAdapterByService($inputs['service_id']);

        return ($adapter::inquiry($inputs['service_id'],$inputs['parameters']));

        exit;

        $makeRequest = $adapter::transaction($inputs['service_id'],'inquiry',0,$inputs['parameters']);

        return $makeRequest;
    }

    public function payment(Request $request){

        $inputs = $request->only([
            'service_id',
            'parameters',
            'amount',
            'inquiry_transaction_id'
        ]);

        if(!$inputs['inquiry_transaction_id']){
            $inquiry_transaction_id = 0;
        }else{
            $inquiry_transaction_id = $inputs['inquiry_transaction_id'];
        }


        if(!$inputs['parameters']){
            $inputs['parameters'] = [];
        }

        $validator = Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
            'parameters'=> 'array',
            'amount'=> 'required|numeric'
        ]);

        if ($validator->fails()){
            return [
                'status'=> false,
                'msg'=> __('Validation Error'),
                'code'=> 103,
                'data'=> $validator->errors()
            ];
        }

        $inputs['parameters'] = array_column($inputs['parameters'],1,0);

        $adapter = Payments::selectAdapterByService($inputs['service_id']);
        
        return ($adapter::payment($inputs['service_id'],$inputs['amount'],$inputs['parameters'],$inquiry_transaction_id));

        exit;

        $makeRequest = $adapter::transaction($inputs['service_id'],'inquiry',0,$inputs['parameters']);

        return $makeRequest;
    }

    public function payment__(Request $request){

        $inputs = $request->only([
            'service_id',
            'parameters',
            'amount'
        ]);

        if(!$inputs['parameters']){
            $inputs['parameters'] = [];
        }

        $validator = Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
            'parameters'=> 'array',
            'amount'=> 'required|numeric'
        ]);

        if ($validator->fails()) {
            return [
                'status'=> false,
                'msg'=> __('Validation Error'),
                'code'=> 103,
                'data'=> (object) [
                    "dddddd" => 'sssssssssss'
                ]
            ];
        }

        $inputs['parameters'] = array_column($inputs['parameters'],1,0);

        $adapter = Payments::selectAdapterByService($inputs['service_id']);
        $makeRequest = $adapter::transaction($inputs['service_id'],'payment',$inputs['amount'],$inputs['parameters']);

        return $makeRequest;
    }

    public function payment2(Request $request){

        $inputs = $request->only([
            'service_id',
            'parameters',
            'amount'
        ]);

        $validator = Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
//            'parameters'=> 'required|array'
        ]);


        if($validator->fails()){
            return ['status'=> false];
        }

/*        $inputs['parameters'] = [
            'parameter_1295'=> '0237800562'
        ];*/

        $adapter = Payments::selectAdapterByService($inputs['service_id']);
        $makeRequest = $adapter::transaction($inputs['service_id'],'payment',$inputs['amount'],$inputs['parameters']);

        return ($makeRequest);
    }

    public function getTotalAmount(Request $request){

        $inputs = $request->only([
            'service_id',
            'amount'
        ]);

        $validator = Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
            'amount'=> 'required|numeric'
        ]);

        if ($validator->fails()) {
            return [
                'status'=> false,
                'msg'=> __('Validation Error'),
                'code'=> 103,
                'data'=> $validator->errors()
            ];
        }

        $adapter = Payments::selectAdapterByService($inputs['service_id']);
        $makeRequest = $adapter::totalAmount($inputs['service_id'],$inputs['amount']);

        return $makeRequest;

    }

    public function __construct(Request $request){
        parent::__construct();
       // $this->middleware('PaymentTransaction');

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


        $data = PaymentServiceAPIs::where('service_type','payment')->get();

        $result = '#,Service Name,Min Value,Max Value,Ex. Amount,Total Amount,Price Type,Commission Type,Commission Value Type'."\n";

        $i = 0;
        foreach ($data as $key => $value){
            $provider = PaymentServices::find($value->id);

            $result.= ++$i;
            $result.= ',';
            $result.= $provider->payment_service_provider->name_ar.' ( '.$value->name.' )';
            $result.= ',';

            $result.= $value->min_value.' LE';
            $result.= ',';
            $result.= $value->max_value.' LE';

            $amount = 0;
            if($value->price_type == 0){
                $amount = rand($value->min_value,$value->max_value);
            }elseif($value->price_type == 1){
                $amount = $value->service_value;
            }elseif($value->price_type == 3){
                $amount = explode(';',$value->service_value_list)[0];
            }

            $totalAmount = 0;
            if($value->commission_type == 0){
                $totalAmount = $amount;
            }elseif(
                $value->commission_type == 1 &&
                $value->commission_value_type == 0
            ){
                $totalAmount = $amount*(1+($value->fixed_commission/100));
            }elseif(
                $value->commission_type == 1 &&
                $value->commission_value_type == 1
            ){
                $totalAmount = $amount+$value->fixed_commission;
            }

            $result.= ',';
            $result.= $amount.' LE';
            $result.= ',';
            $result.= $totalAmount.' LE';
            $result.= "\n";

        }


        echo $result;

        exit;


//
//        echo WalletData::getBalanceTransaction('users',1);
//
//
//
//        exit;
//       //Balance
        //$a = Bee::makeRequest('GetBalance',[]);

//        dd($request->all());
//
//        $transaction = Bee::transaction($request->PaymentTransaction);
//
//
//        print_r($transaction);
//        exit;


       // $data = Bee::handleResponseData('ServiceList',file_get_contents('http://egpay.com/egpay.xml'));


//
//        $adapter = Payments::selectAdapterByID(1);
//
//        $data = Payments::$adapter::serviceList();
//        print_r($data);exit;
//
//
//        $data = $adapter::transaction(4838,'inquiry',0,[
//            'parameter_4838'=>'0237800562'
//        ]);
//
//        $data = Payments::$adapter::serviceList();
//        print_r($data);
//
//
//exit;



        $adapter = Payments::selectAdapterByID(1);

//        dd($adapter::getServiceData(30));return;


        // TRUE Payment :D
        $data = $adapter::transaction(30,'payment',100,[
            'parameter_1295'=>'0237800562'
        ]);
        dd($data);

        // TRUE Inquiry :D
        $data = $adapter::transaction(30,'inquiry',0,[
            'parameter_1294'=>'0237800562'
        ]);
        dd($data);


















//        $adapter = Payments::selectAdapterByID(1);
//
//        $a = ($adapter::getServiceData(29));
//
//        dd($a);
//        return;
//
//        $data = $adapter::transaction(4,'payment',0);
////        $data = $adapter::Balance();
//        dd($data);
//
//
//
//
//        $adapter = Payments::selectAdapterByID(1);
//        $data    = Payments::$adapter::serviceList();
//
//        foreach ($data['providerGroup'] as $key => $value){
//            PaymentServiceProviderCategories::insert([
//                'id'=> $value['id'],
//                'name_ar'=> $value['name'],
//                'name_en'=> $value['name'],
//                'status'=>'active',
//                'staff_id'=> 1
//            ]);
//        }
//
//        foreach ($data['provider'] as $key => $value){
//            PaymentServiceProviders::insert([
//                'id'=> $value['id'],
//                'payment_service_provider_category_id'=> $value['providerGroupId'],
//                'name_ar'=> $value['name'],
//                'name_en'=> $value['name'],
//                'status'=>'active',
//                'staff_id'=> 1
//            ]);
//        }
//
//        foreach ($data['service'] as $key => $value){
//
//            $type = 'payment';
//            if(strpos($value['name'],'nquiry')){
//                $type = 'inquiry';
//            }elseif(strpos($value['name'],'nquire')){
//                $type = 'inquire';
//            }
//
//            $a = PaymentServices::create([
//                'payment_sdk_id'=> '1',
//                'payment_service_provider_id'=> $value['providerId'],
//                'name_ar'=> $value['name'],
//                'name_en'=> $value['name'],
//                'status'=> 'active',
//                'staff_id'=> '1'
//            ]);
//
//
//           PaymentServiceAPIs::insert([
//                'payment_service_id'=> $a->id,
//                'service_type'=> $type,
//                'name'=> $value['name'],
//                'external_system_id'=> $value['accountId'],
//                'price_type'=> $value['priceType'],
//                'service_value'=> $value['serviceValue'],
//                'service_value_list'=> $value['serviceValueList'],
//                'min_value'=> $value['minValue'],
//                'max_value'=> $value['maxValue'],
//                'commission_type'=> $value['commissionType'],
//                'commission_value_type'=> $value['commissionValueType'],
//                'fixed_commission'=> $value['fixedCommission'],
//                'default_commission'=> $value['defaultCommission'],
//                'from_commission'=> $value['fromCommission'],
//                'to_commission'=> $value['toCommission'],
//                'staff_id'=> 1
//            ]);
//        }
//
//        foreach ($data['serviceInputParameter'] as $key => $value){
//            $sdkGroup = PaymentServiceAPIs::where('external_system_id',$value['serviceAccountId'])->first();
//            PaymentServiceAPIParameters::insert([
//                'payment_services_api_id'=> $sdkGroup->id,
//                'external_system_id'=> $value['id'],
//                'name_ar'=> $value['name'],
//                'name_en'=> $value['name'],
//                'position'=> ($value['position'] == 1) ? 'yes' : 'no',
//                'visible' => ($value['visible'] == 1)  ? 'yes' : 'no',
//                'required'=> ($value['required'] == 1) ? 'yes' : 'no',
//                'type'=> $value['type'],
//                'is_client_id'=> ($value['isClientId'] == 1) ? 'yes' : 'no',
//                'default_value'=> $value['defaultValue'],
//                'min_length'=> $value['minLength'],
//                'max_length'=> $value['maxLength'],
//                'staff_id'=> 1
//            ]);
//        }
//
//
//        exit('DDDDOOONE');











        return;









        $data = Bee::serviceList();

        print_r(array_keys($data));
        print_r($data);
        return;

        foreach ($data['providerGroup'] as $key => $value){
            PaymentServiceProviderCategories::insert([
                'id'=> $value['id'],
                'name_ar'=> $value['name'],
                'name_en'=> $value['name'],
                'status'=>'active',
                'staff_id'=> 1
            ]);
        }

        foreach ($data['provider'] as $key => $value){
            PaymentServiceProviders::insert([
                'id'=> $value['id'],
                'payment_service_provider_category_id'=> $value['providerGroupId'],
                'name_ar'=> $value['name'],
                'name_en'=> $value['name'],
                'status'=>'active',
                'staff_id'=> 1
            ]);
        }



        foreach ($data['service'] as $key => $value){
            PaymentSDKGroup::insert([
                'payment_sdk_id'=> 1,
                'name'=> $value['name'],
                'account_id'=> $value['accountId'],
                'price_type'=> $value['priceType'],
                'service_value'=> $value['serviceValue'],
                'service_value_list'=> $value['serviceValueList'],
                'min_value'=> $value['minValue'],
                'max_value'=> $value['maxValue'],
                'commission_type'=> $value['commissionType'],
                'commission_value_type'=> $value['commissionValueType'],
                'fixed_commission'=> $value['fixedCommission'],
                'default_commission'=> $value['defaultCommission'],
                'from_commission'=> $value['fromCommission'],
                'to_commission'=> $value['toCommission'],
                'staff_id'=> 1
            ]);
        }


        foreach ($data['serviceInputParameter'] as $key => $value){
            $sdkGroup = PaymentSDKGroup::where('account_id',$value['serviceAccountId'])->first();
            PaymentSDKGroupParameter::insert([
                'payment_sdk_group_id'=> $sdkGroup->id,
                'name_ar'=> $value['name'],
                'name_en'=> $value['name'],
                'position'=> ($value['position'] == 1) ? 'yes' : 'no',
                'visible' => ($value['visible'] == 1)  ? 'yes' : 'no',
                'required'=> ($value['required'] == 1) ? 'yes' : 'no',
                'type'=> $value['type'],
                'is_client_id'=> ($value['isClientId'] == 1) ? 'yes' : 'no',
                'default_value'=> $value['defaultValue'],
                'min_length'=> $value['minLength'],
                'max_length'=> $value['maxLength'],
                'staff_id'=> 1
            ]);
        }


        exit('Done');

//        print_r(array_keys($data));
//        print_r($data);

        exit;

        // ServiceList
//        $a= Bee::makeRequest('ServiceList',['serviceVersion'=>0]);
//
//        print_r($a);
//        exit;

        // TransAction
//        $a = Bee::makeRequest(
//            'Transaction',
//            [
//                'serviceVersion'=> 173,
//                'transactionId'=> rand(), // From Our System
//                'serviceAccountId'=> 4877,
//                'amount'=> 0,
//                'totalAmount'=> 0,
//                'requestMap'=> [
//                    '261'=> '01282175996'
//                ]
//            ]
//        );


//
//        $a = Bee::makeRequest('TransactionStatus',['transactionId'=> 570706151]);
//
//        print_r($a);
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
            'text'=> __('Users'),
            'url'=> route('system.users.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Create User'),
        ];

        $this->viewData['pageTitle'] = __('Create User');

        $parentID = request('parent_id') ?? old('parent_id');
        if($parentID){
            $parentOf = User::findOrFail($parentID);
            $this->viewData['parentOf'] = $parentOf;
        }

        return $this->view('users.create',$this->viewData);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserFormRequest $request)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('users/'.date('y').'/'.date('m'));
        }

        $theRequest['password'] = bcrypt($theRequest['password']);

        if(!$request->parent_id){
            $theRequest['parent_id'] = null;
        }

        if(User::create($theRequest))
            return redirect()
                ->route('system.users.create')
                ->with('status','success')
                ->with('msg',__('Data has been added successfully'));
        else{
            return redirect()
                ->route('system.users.create')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t add User'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user){

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Users'),
                'url'=> route('system.users.index'),
            ],
            [
                'text'=> $user->firstname.' '.$user->lastname,
            ]
        ];

        $this->viewData['pageTitle'] = __('Show Users');

        $this->viewData['result'] = $user;
        return $this->view('users.show',$this->viewData);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        // Main View Vars
        $this->viewData['breadcrumb'][] = [
            'text'=> __('Users'),
            'url'=> route('system.users.index')
        ];

        $this->viewData['breadcrumb'][] = [
            'text'=> __('Edit User'),
        ];

        $this->viewData['pageTitle'] = __('Edit User');
        $this->viewData['result'] = $user;

        return $this->view('users.create',$this->viewData);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserFormRequest $request, User $user)
    {
        $theRequest = $request->all();
        if($request->file('image')) {
            $theRequest['image'] = $request->image->store('users/'.date('y').'/'.date('m'));
        }else{
            unset($theRequest['image']);
        }

        if($request->password){
            $theRequest['password'] = bcrypt($theRequest['password']);
        }else{
            unset($theRequest['password']);
        }

        if(!$request->parent_id){
            $theRequest['parent_id'] = null;
        }

        if($user->update($theRequest))
            return redirect()
                ->route('system.users.edit',$user->id)
                ->with('status','success')
                ->with('msg',__('Successfully Edit Merchant product Category'));
        else{
            return redirect()
                ->route('system.users.edit')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t Edit User'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Delete Data
        $user->delete();
        if($request->ajax()){
            return ['status'=> true,'msg'=> __('User has been deleted successfully')];
        }else{
            redirect()
                ->route('system.users.index')
                ->with('status','success')
                ->with('msg',__('This User has been deleted'));
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