<?php

namespace App\Modules\Api\Transformers;

class ProductTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'productId'             => $item['id'],
            'name'                  => self::trans($item,'name',$opt),
            'description'           => self::trans($item,'description',$opt),
            'price'                 => $item['price'],
            'active'                => ($item['status'] == 'active') ? true : false,
            'merchantName'          => ((isset($item['merchant_name']))?$item['merchant_name']:self::trans($item['merchant'],'name',$opt)),
            'merchantId'            => ((isset($item['merchant_id']))?$item['merchant_id']:((isset($item['merchant']) && is_array($item['merchant']))?$item['merchant']['merchant_id']:null)),
            //'images'              => self::Link($item,'image'),
            'categoryName'          => self::trans($item['category'],'name',$opt),
            'categoryId'            => $item['category']['id'],
        ];
    }

}