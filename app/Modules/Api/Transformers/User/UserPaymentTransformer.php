<?php

namespace App\Modules\Api\Transformers\User;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Modules\Api\Transformers\Transformer;

class UserPaymentTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        list($date,$time) = explode(' ',$item->dateTime);
        $info = array_column(array_map(function($val,$key)use($opt) {
            if(!is_array($val))
                return ['key' => $key, 'value' => (string) $val];
            else
                return ['key' => $key, 'value' => $val];
        },$item->info,array_keys($item->info)),'value','key');


        if(!isset($info['ar'])){
            $info['ar'] = [];
        }
        if(!isset($info['en'])){
            $info['en'] = [];
        }

        return [
            'transactionID' => $item->transactionId,
            'date' => $date,
            'time' => $time,
            //'ccTransactionId' => $time->ccTransactionId,
            'amount' => (string) $item->system_amount['amount'],// . ' ' . __('LE'),
            'totalAmount' => (string) $item->system_amount['total_amount'],// . ' ' . __('LE'),
            'serviceData' => [
                'merchantId'            =>      $item->service_info['merchant_id'].'-'.Auth::id(),
                'serviceId'             =>      $item->service_info['service_id'],
                'serviceProviderAr'     =>      $item->service_info['provider_name_ar'],
                'serviceNameAr'         =>      $item->service_info['service_name_ar'],
                'serviceNameEn'         =>      $item->service_info['service_name_en'],
                'serviceDescriptionAr'  =>      'Description '.$item->service_info['service_name_ar'],
                'serviceDescriptionEn'  =>      'Description '.$item->service_info['service_name_en'],
                'serviceProviderEn'     =>      $item->service_info['provider_name_en'],
                'paidBy'                =>      $item->payment_by['name'],
                'paidByLogo'            =>      $item->payment_by['logo'],
            ],
            'info'      =>  $info,
            'param'     => $item->param
        ];
    }


    public function inquiryTransform($item,$opt){
        list($date,$time) = explode(' ',$item->dateTime);
        $info = array_column(array_map(function($val,$key)use($opt){
            if(!is_array($val))
                return ['key' => $key, 'value' => (string) $val];
            else
                return ['key' => $key, 'value' => $val];
        },$item->info,array_keys($item->info)),'value','key');

        if(!isset($info['ar'])){
            $info['ar'] = [];
        }
        if(!isset($info['en'])){
            $info['en'] = [];
        }

        return [
            'transactionID' => $item->transactionId,
            'date' => $date,
            'time' => $time,
            //'ccTransactionId' => $time->ccTransactionId,
            'amount' => (string) $item->system_amount['amount'],// . ' ' . __('LE'),
            'totalAmount' => (string) $item->system_amount['total_amount'],// . ' ' . __('LE'),
            'serviceData' => [
                'userId'                =>      Auth::id(),
                'serviceId'             =>      $item->service_info['service_id'],
                'serviceProviderAr'     =>      $item->service_info['provider_name_ar'],
                'serviceNameAr'         =>      $item->service_info['service_name_ar'],
                'serviceNameEn'         =>      $item->service_info['service_name_en'],
                'serviceDescriptionAr'  =>      'Description '.$item->service_info['service_name_ar'],
                'serviceDescriptionEn'  =>      'Description '.$item->service_info['service_name_en'],
                'serviceProviderEn'     =>      $item->service_info['provider_name_en'],
                'paidBy'                =>      $item->payment_by['name'],
                'paidByLogo'            =>      $item->payment_by['logo'],
            ],
            'info'      =>  $info,
            'param'     => $item->param
        ];
    }

    public function paymentTransform($item,$opt){
        list($date,$time) = explode(' ',$item->dateTime);
        $info = array_column(array_map(function($val,$key)use($opt) {
            if(!is_array($val))
                return ['key' => $key, 'value' => (string) $val];
            else
                return ['key' => $key, 'value' => $val];
        },$item->info,array_keys($item->info)),'value','key');


        if(!isset($info['ar'])){
            $info['ar'] = [];
        }
        if(!isset($info['en'])){
            $info['en'] = [];
        }

        return [
            'transactionID' => $item->transactionId,
            'date' => $date,
            'time' => $time,
            //'ccTransactionId' => $time->ccTransactionId,
            'amount' => (string) $item->system_amount['amount'],// . ' ' . __('LE'),
            'totalAmount' => (string) $item->system_amount['total_amount'],// . ' ' . __('LE'),
            'serviceData' => [
                'userId'                =>      Auth::id(),
                'serviceId'             =>      $item->service_info['service_id'],
                'serviceProviderAr'     =>      $item->service_info['provider_name_ar'],
                'serviceNameAr'         =>      $item->service_info['service_name_ar'],
                'serviceProviderEn'     =>      $item->service_info['provider_name_en'],
                'serviceNameEn'         =>      $item->service_info['service_name_en'],
                'paidBy'                =>      $item->payment_by['name'],
                'paidByLogo'            =>      $item->payment_by['logo'],
            ],
            'info'      =>  $info,
            'param'     => $item->param
        ];
    }

    public function InvoiceTransformer($item,$opt)
    {
        //return $item;
        return [
            'id'                            =>          $item['id'],
            'provider_name'                 =>          $item['payment_transaction']['payment_services']['payment_service_provider']['name'],
            'provider_service'              =>          $item['payment_transaction']['payment_services']['name'],
            'status'                        =>          $item['status'],
            'created_at'                    =>          Carbon::createFromFormat('Y-m-d H:i:s',$item['created_at'])->diffForHumans(),
            'logo'                          =>          self::Link($item['payment_transaction']['payment_services']['payment_service_provider'],'logo'),
            //'logo'                          =>           'data:image/png;base64,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',
            'total_amount'                  =>          number_format($item['total_amount'],3),
        ];
    }

    public function OneInvoice($item,$opt)
    {
        list($date,$time) = explode(' ',$item->dateTime);
        $info = array_column(array_map(function($val,$key)use($opt) {
            if(!is_array($val))
                return ['key' => $key, 'value' => (string) $val];
            else
                return ['key' => $key, 'value' => $val];
        },$item->info,array_keys($item->info)),'value','key');


        if(!isset($info['ar'])){
            $info['ar'] = [];
        }
        if(!isset($info['en'])){
            $info['en'] = [];
        }

        return [
            'transactionID' => $item->transactionId,
            'date' => $date,
            'time' => $time,
            //'ccTransactionId' => $time->ccTransactionId,
            'amount' => (string) $item->system_amount['amount'],// . ' ' . __('LE'),
            'totalAmount' => (string) $item->system_amount['total_amount'],// . ' ' . __('LE'),
            'serviceData' => [
                'userId'                =>      Auth::id(),
                'serviceId'             =>      $item->service_info['service_id'],
                'serviceProviderAr'     =>      $item->service_info['provider_name_ar'],
                'serviceNameAr'         =>      $item->service_info['service_name_ar'],
                'serviceProviderEn'     =>      $item->service_info['provider_name_en'],
                'serviceNameEn'         =>      $item->service_info['service_name_en'],
                'paidBy'                =>      $item->payment_by['name'],
                'paidByLogo'            =>      $item->payment_by['logo'],
            ],
            'info'      =>  $info,
            'param'     => $item->param
        ];
    }


    public function WalletTransactions($item,$opt)
    {
        return [
            'transactionID'                 => $item['id'],
            'amount'                        => self::Round($item['amount']),
            'isPaid'                        => (($item['status']=='paid')?true:false),
            'fromName'                      => self::WalletOwnerName($item['from_wallet']['walletowner'],$opt),
            'fromType'                      => self::walletOwnerType($item['from_wallet']['walletowner']),
            'toName'                        => self::WalletOwnerName($item['to_wallet']['walletowner'],$opt),
            'toType'                        => self::walletOwnerType($item['to_wallet']['walletowner']),
        ];
    }

    public function Transfer($item,$opt)
    {
        /*
         * Transfer status
         */
        if($item['status']===false){
            switch($item['error_code']){
                //Amount must be number
                case 1:
                    return self::Failtransfer(__('Amount must be a valid number'),$item);
                    break;
                //Not enough balance
                case 2:
                    return self::Failtransfer(__('You do not have enough credit to make this transfer'),$item);
                    break;
                //Can't Transfer to yourself
                case 6:
                    return self::Failtransfer(__('Can not transfer credit to yourself'),$item);
                    break;
                //Transaction type not in [Wallet,Cash]
                case 3:
                    //Error transaction status
                case 4:
                    //User model can't make this transaction
                case 5:
                default:
                    return self::Failtransfer(__('Can not make such transaction, transaction not processed'),$item);
                    break;
            }
        } else {
            return [
                'transactionID' => $item['id'],
                'amount' => $item['amount'] . ' ' . __('LE'),
                'isPaid' => (($item['status'] == 'paid') ? true : false),
                'toName' => ((array_key_exists('to_wallet',$item))?self::WalletOwnerName($item['to_wallet']['walletowner'], $opt):''),
                'toId' => $item['to_id'],
            ];
        }
    }



    private static function WalletOwnerName($item,$opt){
        if(isset($item['merchant_category_id']))
            return self::trans($item,'name',$opt);
        elseif(isset($item['unique_name']))
            return __('System Wallet');
        else
            return $item['mobile'];
    }

    private static function Failtransfer($msg,$item){
        $item['msg'] = $msg;
        return $item;
    }

}