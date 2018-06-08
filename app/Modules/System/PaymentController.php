<?php

namespace App\Modules\System;

use App\Libs\Commission;
use App\Libs\Payments\Adapters\Bee;
use App\Libs\WalletData;
use App\Models\PaymentInvoice;
use App\Models\PaymentServices;
use App\Models\Staff;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Auth;

use Validator;

class PaymentController extends SystemController
{

    public function __construct(){
        parent::__construct();
        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ],
            [
                'text'=> __('Payment')
            ]
        ];
    }

    public function index(Request $request){
        if($request->ajax){
            $data = [];
            $data['sdk_wallet']       = amount((string) Bee::balance()->data->balance,true);
            $data['egpay_wallet']     = amount(WalletData::balance(setting('main_wallet_id')),true);
            $data['supervisor_wallets'] = amount(
                Wallet::where('type','payment')
                    ->where('walletowner_type','App\Models\Staff')
                    ->whereIn('walletowner_id',[Staff::where('permission_group_id',5)->get()->toArray()])
                    ->selectRaw("SUM(`balance`) as `sum`")
                    ->first()
                    ->sum,
                true);
            $data['sales_wallets'] = amount(
                Wallet::where('type','payment')
                    ->where('walletowner_type','App\Models\Staff')
                    ->whereIn('walletowner_id',[Staff::where('permission_group_id',6)->get()->toArray()])
                    ->selectRaw("SUM(`balance`) as `sum`")
                    ->first()
                    ->sum,
                true);
            $data['merchant_wallets'] = amount(
                Wallet::where('type','payment')
                    ->where('walletowner_type','App\Models\Merchant')
                    ->selectRaw("SUM(`balance`) as `sum`")
                    ->first()
                    ->sum,
                true);


            $paymentInvoice = PaymentInvoice::whereRaw("DATE(`created_at`) = ?",[date('Y-m-d')])
                ->where('status','paid')
                ->selectRaw("SUM(`total_amount`) as `sum`")
                ->selectRaw("COUNT(`id`) as `count`")
                ->first();

            $data['invoices_amount'] = amount($paymentInvoice->sum,true);
            $data['invoices_count']  = $paymentInvoice->count;

            $commission = Commission::paymentSettlement(date('Y-m-d 00:00:00'),date('Y-m-d 23:59:59'));
            $data['system_commission']  =@ amount(array_sum(array_column($commission['data']->settlement,'system_commission')),true);
            $data['merchant_commission']  = @amount(array_sum(array_column($commission['data']->settlement,'merchant_commission')),true);


            $services = PaymentInvoice::join('payment_transactions', 'payment_transactions.id', '=', 'payment_invoice.payment_transaction_id')
                ->join('payment_services', 'payment_services.id', '=', 'payment_transactions.payment_services_id')
                ->join('payment_service_providers', 'payment_service_providers.id', '=', 'payment_services.payment_service_provider_id')
                ->where('payment_invoice.status', '=', 'paid')
                ->whereRaw('DATE(payment_invoice.created_at) = ?',[date('Y-m-d')])
                ->groupBy('payment_services.id')
                ->orderBy('count','DESC')
                ->select([
                    'payment_services.name_en as payment_services_name',
                    'payment_service_providers.name_en as payment_service_providers_name',
                    \DB::raw('COUNT(*) as `count`'),
                    \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
                ])
                ->get();

            $data['services'] = '';
            foreach ($services as $key => $value){
                $data['services'] .= '<tr>'.
                    '<td>'.$value->payment_service_providers_name.' '.$value->payment_services_name.'</td>'.
                    '<td>'.amount($value->total_amount,true).'</td>'.
                    '<td>'.$value->count.'</td>'.
                '</tr>';
            }



            return $data;
        }

        $this->viewData['pageTitle'] = __('Payment Status');

        return $this->view('payment.payment.index',$this->viewData);

    }

    public function summary(Request $request){

        $eloquentData = PaymentInvoice::join('payment_transactions', 'payment_transactions.id', '=', 'payment_invoice.payment_transaction_id')
            ->join('payment_services', 'payment_services.id', '=', 'payment_transactions.payment_services_id')
            ->join('payment_service_providers', 'payment_service_providers.id', '=', 'payment_services.payment_service_provider_id')
            ->groupBy('payment_services.id')
            ->orderBy('count','DESC')
            ->select([
                'payment_services.name_en as payment_services_name',
                'payment_service_providers.name_en as payment_service_providers_name',
                \DB::raw('COUNT(*) as `count`'),
                \DB::raw('SUM(`payment_invoice`.`total_amount`) as `total_amount`')
            ]);

        whereBetween($eloquentData,'payment_invoice.created_at',$request->created_at1,$request->created_at2);

        if($request->status){
            $eloquentData->where('payment_invoice.status', '=', $request->status);
        }else{
            $eloquentData->where('payment_invoice.status', '=', 'paid');
        }

        if($request->payment_service_id){
            $eloquentData->where('payment_services.id', '=', $request->payment_service_id);
        }

        whereBetween($eloquentData,'payment_invoice.total_amount',$request->total_amount1,$request->total_amount2);

        $this->viewData['result'] = $eloquentData->get();

        $this->viewData['paymentServices'] = PaymentServices::where('status','=','active')
            ->pluck('name_'.$this->systemLang,'id')
            ->reverse()->put('0',__('Select Service'))->reverse()->toArray();
        $this->viewData['pageTitle'] = __('Payment Summary report');

        return $this->view('payment.payment.summary',$this->viewData);
    }
}
