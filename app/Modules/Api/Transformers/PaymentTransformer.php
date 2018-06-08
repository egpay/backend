<?php

namespace App\Modules\Api\Transformers;

use Illuminate\Support\Facades\Auth;

class PaymentTransformer extends Transformer
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
                'merchantId'            =>      Auth::user()->merchant()->id.'-'.Auth::id(),
                'serviceId'             =>      $item->service_info['service_id'],
                'serviceProviderAr'     =>      $item->service_info['provider_name_ar'],
                'serviceNameAr'         =>      $item->service_info['service_name_ar'],
                'serviceNameEn'         =>      $item->service_info['service_name_en'],
                //TODO Static Descrption
                'serviceDescriptionAr'  =>      $item->service_info['service_description_ar'],
                'serviceDescriptionEn'  =>      $item->service_info['service_description_en'],
                'serviceProviderEn'     =>      $item->service_info['provider_name_en'],
                'paidBy'                =>      $item->payment_by['name'],
                'paidByLogo'            =>      $item->payment_by['logo'],
            ],
            'info'      =>  $info,
            'param'     => $item->param
        ];
    }

}