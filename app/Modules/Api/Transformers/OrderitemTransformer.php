<?php

namespace App\Modules\Api\Transformers;

class OrderitemTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'itemId'                => $item['id'],
            'orderId'               => $item['order_id'],
            'productId'             => $item['merchant_product_id'],
            'price'                 => $item['price'],
            'quantity'              => $item['qty'],
            'productName'           => ((isset($item['merchant_product']))?self::trans($item['merchant_product'],'name',$opt):null),
            'options'               => ((isset($item['order_item_attribute']))?$this->ProductAttributes($item['order_item_attribute']):null),
        ];
    }

    private function ProductAttributes($attributes){
        $newattr = recursiveFind($attributes,'attribute_data');
        return implode(' | ',$newattr);
    }

}