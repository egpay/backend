<?php

namespace App\Modules\Api\Transformers;


use App\Modules\Api\Merchant\MerchantApiController;
use App\Modules\Merchant\MerchantController;

class PermissionTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        $perms = array_map(function($oneperm){return $oneperm['route_name'];},$item);
        $MerchantController = new MerchantController();
        if(is_array($item) && count($item)){
            $curPermission = [];
            $permissions = (new MerchantApiController())->permissions();
            foreach($permissions as $key=>$val){
                if(self::Can($val,$perms))
                    array_push($curPermission,['name'=>$MerchantController->permissionsNames($key),'permission'=>self::Can($val,$perms)]);
            }
            return $curPermission;
        } else {
                return __('By default this group have all permissions');
        }
    }


    private static function Can($val,$perms){
        if(array_diff($val,$perms))
            return false;
        else
            return true;
    }
}