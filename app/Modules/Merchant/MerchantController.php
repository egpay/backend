<?php

namespace App\Modules\Merchant;

use App\Http\Controllers\Controller;
use App\Models\MerchantStaffGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Auth;
use League\Flysystem\File;


class MerchantController extends Controller{

    protected $systemLang;
    protected $viewData = [];

    public function __construct(){
        $this->middleware(['auth:merchant_staff','merchantcan:'.request()->route()->getName().'']);
        $this->systemLang = App::getLocale();
        $this->viewData['systemLang'] = $this->systemLang;
        $this->viewData['paymenttype'] = [
            '0'=>__('Select type'),
            'wallet'=>__('Wallet'),
            'cash'=>__('Cash'),
        ];
    }


    protected function view($file,array $data = []){
        return view('merchant.'.$file,$data);
    }

    public function access_denied()
    {
        dd(Session::get('msg'));
    }


    public function permissions($permission=false){
        $permissions = \Illuminate\Support\Facades\File::getRequire('../app/Modules/Merchant/Permissions.php');
        return $permission ? isset($permissions[$permission]) ? $permissions[$permission] : false : $permissions;
    }

    public function permissionsNames($permission=false,$reverse=false){
        $permissions = $this->permissions();
        $data = [];
        foreach($permissions as $key=>$val){
            $data = array_merge($data,[$key=>__(ucfirst(str_replace('-',' ',$key)))]);
        }
        if($reverse)
            return array_search($permission,$data);
        else
            return $data ? isset($data[$permission]) ? $data[$permission] : false : $data;
    }



}