<?php

namespace App\Modules\Api\Transformers;

class ProductCategoryTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'productCategoryId'             => $item['id'],
            'name'                          => self::trans($item,'name',$opt),
            'description'                   => self::trans($item,'description',$opt),
            'isActive'                      => self::status($item),
            'merchantName'                  => ((isset($item['merchant_name']))?$item['merchant_name']:self::trans($item['merchant'],'name',$opt)),
        ];
    }
}