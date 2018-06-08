<?php

namespace App\Modules\Api\Transformers;

class SubMerchantTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'subMerchantId'             => $item['id'],
            'name'                      => $item['name'],
            'logo'                      => self::Link($item,'logo'),
            'categoryName'              => $item['category_name'],
            'categoryId'                => $item['merchant_category_id'],
            'address'                   => $item['address'],
            /*
            'Staff'                     => ['staffId'       => $item['staff_id'],
                                            'firstname'     => $item['staff_firstname'],
                                            'lastname'      => $item['staff_lastname']],
            */
            'staffCount'                => $item['count_staff'],
            'staffgroupCount'           => $item['count_staff_group'],
            'branchesCount'             => $item['count_branches'],
            'isActive'                  => self::status($item),
        ];
    }
}