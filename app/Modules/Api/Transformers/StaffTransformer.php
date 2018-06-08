<?php

namespace App\Modules\Api\Transformers;

class StaffTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'staffId'       => $item['id'],
            'staffName'     => self::fullName($item),
            'isActive'      => self::status($item),
            'type'          => 'systemStaff'
        ];
    }
}