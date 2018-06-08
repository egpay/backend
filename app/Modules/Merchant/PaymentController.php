<?php

namespace App\Modules\Merchant;

use App\Libs\Payments\Payments;
use App\Libs\WalletData;
use App\Models\Merchant;
use App\Models\Wallet;
use App\Modules\Api\Transformers\TransferTransformer;
use Illuminate\Support\Facades\Validator;
use App\Models\PaymentServiceAPIParameters;
use App\Models\PaymentServiceAPIs;
use App\Models\PaymentServices;
use Illuminate\Http\Request;
use Auth;
use Mockery\Exception;

class PaymentController extends MerchantController
{

    protected $viewData;

    public function index(Request $request){
        $this->viewData['pageTitle'] = __('Payment');
        $this->viewData['services'] = PaymentServices::viewData($this->systemLang,[])->get();
        //dd(($this->viewData['services']->groupBy('category_id')));
        $this->viewData['balance'] = Auth::user()->paymentWallet->balance;

        return $this->view('payment.index',$this->viewData);
    }


    public function service($id,$specificType=false){
        $this->viewData['pageTitle'] = __('Payment');
        $lang = $this->systemLang;
        $button = __('Inquiry');
        $type = 'inquiry';
        $service = PaymentServiceAPIs::viewData($this->systemLang,[])
            ->where('payment_service_apis.payment_service_id','=',$id)
            ->where('payment_service_apis.service_type','=','inquiry')
            ->with(['payment_service_api_parameters'=>function($sql)use($lang){
                $sql->select([
                    'external_system_id','payment_services_api_id','position',
                    'visible','required','type','is_client_id',
                    'default_value','min_length','max_length',
                    'name_'.$lang.' as name'])
                    ->orderBy('position')
                    ->where('visible','=','yes');
            }])
            ->first();

        if(!$service || ($specificType == 'payment')){

            $service = PaymentServiceAPIs::viewData($this->systemLang,[])
                ->where('payment_service_apis.payment_service_id','=',$id)
                ->where('payment_service_apis.service_type','=','payment')
                ->with(['payment_service_api_parameters'=>function($sql)use($lang){
                    $sql->select([
                        'external_system_id','payment_services_api_id','position',
                        'visible','required','type','is_client_id',
                        'default_value','min_length','max_length',
                        'name_'.$lang.' as name'])
                        ->orderBy('position')
                        ->where('visible','=','yes');
                }])
                ->first();
            if(count($service->payment_service_api_parameters)){
                $button = __('Pay');
                $type = 'payment';
            } else {
                $button = __('Pre-paid card');
                $type = 'prepaid';
                $total = $this->getTotalAmount($service->id,0);
                $service->total_amount = number_format($total['data']->total_amount,2);
            }
        }

        if($service)
            return [$service,'type'=>$type,'lang'=>['button'=>$button]];
        else
            return false;

    }

    public function inquiry($id,Request $request){
        $request['service_id'] = $id;
        $inputs = $request->only([
            'service_id',
            'parameters'
        ]);

        Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
            'parameters'=> 'array'
        ])->validate();

        $params = [];
        if((isset($inputs['parameters'])) && (count($inputs['parameters']))) {
            foreach ($inputs['parameters'] as $key => $val) {
                $paramid = explode('_', $key)['1'];
                $paramRow = PaymentServiceAPIParameters::where('external_system_id', '=', $paramid)->first();
                $params[str_replace(' ','_',strtolower($paramRow->name_en))] = $key;
            }
        }

        $adapter = Payments::selectAdapterByService($inputs['service_id']);

        $response = $adapter::inquiry($inputs['parameters']);

        if($response['status']){
            $response['data']->service_info['merchant_id'] = Auth::user()->merchant()->id.'-'.Auth::id();
            return array_merge($response,['service'=>$this->service($id,'payment')],['params'=>$params]);
        }
        return $response;
    }



    public function payment($id,Request $request){
        $request['service_id'] = $id;
        $inputs = $request->only([
            'service_id',
            'parameters',
            'amount',
            'inquiry_transaction_id'
        ]);


        if(!$inputs['parameters']){
            $inputs['parameters'] = [];
        }

        if(!$inputs['amount']) {
            $data = $this->getTotalAmount($id, 0);
            if (isset($data['data']->amount)){
                $inputs['amount'] = number_format($data['data']->amount, 2);
            } else {
                $inputs['amount'] = number_format(0, 2);
            }
            //$inputs['amount'] = $data['data']->amount;
        }

        if(!$inputs['inquiry_transaction_id']){
            $inquiry_transaction_id = 0;
        }else{
            $inquiry_transaction_id = $inputs['inquiry_transaction_id'];
        }

        Validator::make($inputs,[
            'service_id'=> 'required|numeric|exists:payment_services,id',
            'parameters'=> 'array',
            'amount'=> 'required|numeric'
        ])->validate();


        $adapter = Payments::selectAdapterByService($inputs['service_id']);
        $response = $adapter::payment($inputs['parameters'],$inquiry_transaction_id,$inputs['amount']);
        if($response['status']) {
            $response['data']->service_info['merchant_id'] = Auth::user()->merchant()->id . '-' . Auth::id();
        }
        $response['balance'] = number_format(Auth::user()->paymentwallet()->first()->balance, 2) . ' ' . __('LE');
        return $response;
    }


    private function getTotalAmount($serviceId,$amount){

        $adapter = Payments::selectAdapterByService($serviceId);
        $makeRequest = $adapter::totalAmount($amount);

        return $makeRequest;

    }

    public function totalAmount($id,Request $request){
        $inputs = $request->only(['amount']);
        $inputs['service_id'] = $id;
        $validator = Validator::make($inputs, [
            'service_id' => 'required|numeric|exists:payment_services,id',
            'amount' => 'required|numeric'
        ]);
        $status = true;
        if($validator->errors()->any()){
            $status = false;
            $msg = implode('<br>',array_flatten($validator->errors()->messages()));
            return ['status'=>$status,'msg'=>$msg];
        }
        $response = $this->getTotalAmount($id,$inputs['amount']);
        if(!$response['status']){
            return ['status'=> false,'msg'=>$response['msg']];
        }
        return number_format($response['data']->total_amount,2).' '.__('LE');
    }

    public function transfer(Request $request){
        $this->viewData['pageTitle'] = __('Transfer');
        $this->viewData['balance'] = Auth::user()->paymentWallet->balance;
        return $this->view('payment.transfer',$this->viewData);
    }



    public function transferDo(Request $request){
        $inputs = $request->only(['amount','wallet_id','wallet_id_confirmation']);
        $validator = Validator::make($inputs, [
            'wallet_id' => 'required|numeric|exists:wallet,id|confirmed',
            'amount' => 'required|numeric'
        ]);
        $status = true;
        if($validator->errors()->any()){
            $status = false;
            $msg = implode('<br>',array_flatten($validator->errors()->messages()));
            return ['status'=>$status,'msg'=>$msg];
        }
        $OwnerWallet = Auth::user()->paymentWallet;
        $wallet = Wallet::where('id',$request->wallet_id)->with('walletowner')->first();

        if(!isset($OwnerWallet)){
            $status = false;
            $msg = __('Your wallet not ready yet');
        }

        if($inputs['amount'] > $OwnerWallet->balance){
            $status = false;
            $msg = __('Not enough credit');
        }

        if(!$wallet){
            $status = false;
            $msg = __('Can not transfer at this time');
        }

        WalletData::makeTransactionWithoutModel(true);
        $transfer = WalletData::makeTransaction(
            $inputs['amount'],
            'wallet',
            $OwnerWallet->id,
            $wallet->id,
            null,
            null,
            'App\Models\MerchantStaff',
            Auth::id(),
            'paid'
        );

        if(!$transfer['status']) {
            if($transfer['code']==6)
                return ['status'=>false,'msg'=>__('Can\'t transfer to yourself')];
            else
                return $transfer;
        }

        if ($transfer['status']) {
            $transfer['to_wallet'] = $wallet;
        }
        $transfer['balance'] = number_format(Auth::user()->paymentWallet()->first()->balance,2).' '.__('LE');

        return $transfer;

    }

}