<?php

namespace App\Modules\Api\Transformers;

class KnowledgeTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'knowledgeId'           => ((isset($item['id']))?$item['id']:null),
            'name'                  => self::trans($item,'name',$opt),
            'Content'               => self::trans($item,'content',$opt),
            'merchantstaffId'       => $item['merchant_staff_id']
        ];
    }
}