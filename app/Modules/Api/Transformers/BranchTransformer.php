<?php

namespace App\Modules\Api\Transformers;

class BranchTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'branchId'                  => $item['id'],
            'name'                      => self::trans($item,'name',$opt),
            'address'                   => self::trans($item,'address',$opt),
            'LngLat'                    => $item['latitude'].','.$item['longitude'],
            'merchantName'              => ((isset($item['merchant_name']))?$item['merchant_name']:self::trans($item,'name',$opt)),
            'merchantId'                => ((isset($item['merchant_id']))?$item['merchant_id']:((isset($item['merchant']['id']))?$item['merchant']['id']:null)),
            'isActive'                  => self::status($item),
            'logo'                      => self::Link($item,'logo'),
            'area'                      => (new AreaTransformer())->transform($item['area'],$opt),
        ];
    }
}