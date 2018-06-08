<?php

namespace App\Modules\Api\Transformers;

class MailTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'mailId'            => $item['id'],
            'subject'           => $item['subject'],
            'body'              => $item['body'],
            'file'              => $item['file'],
            'created_at'        => $item['created_at'],
            'sender'            => self::fullName($item['sendermodel']),
            'receiver'          => ((isset($item['receiver']))?(new EmailReveiverTransformer())->transformCollection($item['receiver'],[$opt]):null),
            'sender'            => (($item['sendermodel_type']=='App\Models\Staff')?
                                    (new StaffTransformer())->transform($item['sendermodel'],$opt)
                                    :null),
        ];
    }
}