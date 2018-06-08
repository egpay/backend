<?php

namespace App\Modules\Api\Transformers;

use App\Libs\AreasData;

class AreaTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'areaId'    => $item['id'],
            'name'      => self::trans($item,'name',$opt),
            'lat'       => $item['latitude'],
            'lng'       => $item['longitude'],
            'parent'    =>((isset($item['parent']))?$this->transform($item['parent'],$opt):false)
        ];
    }
}