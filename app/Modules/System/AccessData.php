<?php

namespace App\Modules\System;

use Illuminate\Http\Request;
use Auth;
use DB;

class AccessData extends SystemController{


    public function __construct(Request $request){
        parent::__construct();

        $this->viewData['breadcrumb'] = [
            [
                'text'=> __('Home'),
                'url'=> url('system'),
            ]
        ];
    }


    public function index(Request $request){
        $this->viewData['pageTitle'] = __('Access User Data');
        return $this->view('access-data.index',$this->viewData);
    }



}