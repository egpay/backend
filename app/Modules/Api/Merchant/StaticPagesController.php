<?php
namespace App\Modules\Api\Merchant;

use Auth;

class StaticPagesController extends MerchantApiController
{

    public function aboutUs(){
        return $this->respondSuccess([
            'title'         => 'About us Page',
            'html'          =>'<div>Test Page Test Page Test Page Test Page Test Page </div>'
        ]);
    }



    public function checkversion(){
        return $this->respondSuccess(true);
    }


    public function apk(){
        return response()->download(storage_path('app/public/app.apk'),'latest-apk.apk');
    }



}