<?php

namespace App\Modules\Api\Transformers;

class OrderTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'orderId'               => $item['id'],
            'total'                 => $item['total'],
            'isPaid'                => self::isPaid($item),
            'branchName'            => ((isset($item['branch_name']))?$item['branch_name']:((isset($item['merchant_branch']))?self::trans($item['merchant_branch'],'name',$opt):null)),
            'branchId'              => $item['merchant_branch_id'],
            'orderItems'            => count($item['orderitems']),
            'items'                 => (new OrderitemTransformer())->transformCollection($item['orderitems'],[$opt]),
            'transactions'          => (new TransactionTransformer())->transformCollection($item['trans'],[$opt]),
        ];
    }
}