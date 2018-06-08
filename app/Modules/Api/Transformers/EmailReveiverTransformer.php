<?php

namespace App\Modules\Api\Transformers;

class EmailReveiverTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'receiverId'        => $item['id'],
            'emailId'           => $item['email_id'],
            'isStared'          => (($item['star']=='yes')?true:false),
            'isSeen'            => (($item['seen'])?true:false),
            'receiver'          => (($item['receivermodel_type']=='App\Models\MerchantStaff')?
                                            (new MerchantStaffTransformer())->transform($item['receivermodel'],$opt)
                                            :null),//receiver not a staff merchant
        ];
    }
}