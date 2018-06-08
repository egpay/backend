<?php

function getWalletOwnerName(&$wallet,$systemLang,$linkType = null){
    $walletOwner = $wallet->walletowner;

    $url = '';
    if($linkType == 'wallet'){
        $url = route('system.wallet.show',$wallet->id);
    }

    switch(get_class($walletOwner)){
        case 'App\Models\Staff':
            if($linkType == 'profile')
                $url = route('system.staff.show',$walletOwner->id);
            $return = $wallet->walletowner->firstname.' '.$wallet->walletowner->lastname;
        break;
        case 'App\Models\User':
            if($linkType == 'profile')
                $url = route('system.user.show',$walletOwner->id);
            $return = $wallet->walletowner->firstname.' '.$wallet->walletowner->lastname;
        break;

        case 'App\Models\Merchant':
            if($linkType == 'profile')
                $url = route('merchant.merchant.show',$walletOwner->id);
            $return = $walletOwner->{'name_'.$systemLang};
        break;

        case 'App\Models\MainWallets':
            if($linkType == 'profile')
                $url = route('system.wallet.main-wallets');
            $return = $walletOwner->name;
        break;

        default:
            $return = '[UNKNOWN]';
        break;
    }

    if(!empty($url)){
        return '<a href="'.$url.'">'.$return.'</a>';
    }

    return $return;
}