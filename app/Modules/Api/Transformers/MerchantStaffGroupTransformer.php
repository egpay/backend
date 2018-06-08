<?php

namespace App\Modules\Api\Transformers;

class MerchantStaffGroupTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'groupId'                           => $item['id'],
            'groupTitle'                        => $item['title'],
            'merchantName'                      => ((isset($item['name']))?$item['name']:self::trans($item['merchant'],'name',$opt)),
            'merchantId'                        => ((isset($item['merchant_id']))?$item['merchant_id']:$item['merchant']['id']),
            'permissions'                       => self::permissions($item,$opt),
        ];
    }


    private static function permissions($item,$opt){
        if((isset($item['permissions']) && count($item['permissions']))){
            return (new PermissionTransformer())->transform($item['permissions'],[$opt]);
        } else {
            if(request()->user()->merchant->merchant_staff_group()->first()->id == $item['id']){
                return __('By default this group have all permissions');
            } else {
                return null;
            }
        }

    }
}