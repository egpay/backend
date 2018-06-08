<?php

namespace App\Modules\Api\Transformers;

class NewsTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'newsId'                => $item['id'],
            'name'                  => self::trans($item,'name',$opt),
            'brief'                 => str_limit(strip_tags(self::trans($item,'content',$opt)),100),
            'content'               => self::trans($item,'content',$opt),
            'image'                 => self::Link($item,'image'),
            'categoryName'          => ((isset($item['category_name']))?$item['category_name']:null),
            'categoryDescription'   => ((isset($item['category_description']))?$item['category_description']:null),
            'categoryId'            => ((isset($item['category_id']))?$item['category_id']:null),
        ];
    }
}