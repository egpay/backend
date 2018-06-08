<?php

namespace App\Modules\Api\Transformers;

class TransactionTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'transactionID'                 => $item['id'],
            'amount'                        => $item['amount'],
            'isPaid'                        => (($item['status']=='paid')?true:false),
            'fromName'                      => self::WalletOwnerName($item['from']['walletowner'],$opt),
            'fromType'                      => self::walletOwnerType($item['from']['walletowner']),
            'toName'                        => self::WalletOwnerName($item['to']['walletowner'],$opt),
            'toType'                        => self::walletOwnerType($item['to']['walletowner']),
        ];
    }

    private static function WalletOwnerName($item,$opt){
        if(isset($item['merchant_category_id']))
            return self::trans($item,'name',$opt);
        else
            return $item['mobile'];
    }
}