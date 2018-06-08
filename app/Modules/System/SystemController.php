<?php

namespace App\Modules\System;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SystemController extends Controller{

    protected $systemLang;
    protected $user = null;
    protected $viewData = [];

    public function __construct(){
        $this->middleware(['auth:staff','staffcan:'.request()->route()->getName().''])->except(['test_perms']);
        $this->systemLang = App::getLocale();
        $this->viewData['systemLang'] = $this->systemLang;
        $this->user = Auth::user();
    }


    protected function view($file,array $data = []){
        return view('system.'.$file,$data);
    }

    public function access_denied()
    {
        dd('Access Denied '.Session::get('msg'));
    }


    public function permissions($permission=false){
        $permissions = \Illuminate\Support\Facades\File::getRequire('../app/Modules/System/Permissions.php');
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

    public function test_perms(){
        $permissions = array();
        $perms = recursiveFind($this->permissions(),'permissions');
        foreach($perms as $val){
            foreach($val as $key=>$oneperm){
                $permissions[$key] = $oneperm;
            }
        }

        foreach ($permissions as $key=>$val){
            foreach($val as $perm){
                try{
                    if(strpos($perm,'show') || strpos($perm,'destroy') || strpos($perm,'edit') || strpos($perm,'update') || strpos($perm,'qrcode')
                        || strpos($perm,'change-status') || strpos($perm,'appointment-datetime') || strpos($perm,'get-conversation')
                    ){
                        echo route($perm,1).'<br>';
                    } else {
                        echo route($perm).'<br>';
                    }
                } catch (\Exception $e){
                    dd($e);
                }
            }
            echo "<hr>";
        }
        die;
    }
}