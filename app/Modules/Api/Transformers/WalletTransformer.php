<?php

namespace App\Modules\Api\Transformers;


class WalletTransformer extends Transformer
{
    public function transform($item,$opt)
    {
        return [
            'balance'=> $item['balance'],
        ];
    }
}