<?php

namespace App\Modules\Api\Transformers;

class MerchantStaffTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'merchantStaffId'       => $item['id'],
            'firstName'              => ((isset($item['firstname']))?$item['firstname']:null),
            'lastName'              => ((isset($item['lastname']))?$item['lastname']:null),
            'isActive'              => self::status($item),
            'type'                  => 'merchantStaff',
            'nationalId'            => ((isset($item['national_id']))?$item['national_id']:null),
            'emailAddress'          => ((isset($item['email']))?$item['email']:null),
            'jobTitle'              => ((isset($item['title']))?$item['title']:null),
            'staffGroup'            => ((isset($item['merchant_staff_group']))?$item['merchant_staff_group']['title']:null),
            'staffgroupId'          => ((isset($item['merchant_staff_group']))?$item['merchant_staff_group']['id']:null),
        ];
    }
}