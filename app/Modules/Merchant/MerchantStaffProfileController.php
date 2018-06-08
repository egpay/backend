<?php

namespace App\Modules\Merchant;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MerchantStaffProfileController extends MerchantController
{
    protected $viewData;

    public function edit(Request $request){
        $this->viewData['merchant'] = $request->user()->merchant();
        $this->viewData['pageTitle'] = __('Profile edit');
        return $this->view('profile.edit',$this->viewData);
    }


    public function update(Request $request){
        $RequestData = $request->only(['firstname','lastname']);

        Validator::make($RequestData,[
            'firstname'         =>    'required',
            'lastname'          =>    'required'
        ])->validate();

        if(Auth::user()->update($RequestData))
            return redirect()
                ->route('panel.merchant.user.update-info')
                ->with('status','success')
                ->with('msg',__('Data has been edited successfully'));
        else{
            return redirect()
                ->route('panel.merchant.user.update-info')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t update your profile info'));
        }

    }


    public function editPassword(Request $request){
        $this->viewData['merchant'] = $request->user()->merchant();
        $this->viewData['pageTitle'] = __('Profile edit');
        return $this->view('profile.change-password',$this->viewData);
    }


    public function updatePassword(Request $request){
        $RequestData = $request->only(['current_password','password','password_confirmation']);

        Validator::make($RequestData,[
            'current_password'  => 'required|PasswordCheck:'.Auth::user()->password,
            'password'          => 'required|confirmed|min:6',
        ])->validate();

        if(Auth::user()->update([
            'password'              => bcrypt($RequestData['password']),
            'must_change_password'  =>0,
        ]))
            return redirect()
                ->route('panel.merchant.user.change-password')
                ->with('status','success')
                ->with('msg',__('Password successfully changed'));
        else{
            return redirect()
                ->route('panel.merchant.user.change-password')
                ->with('status','danger')
                ->with('msg',__('Sorry Couldn\'t change your password'));
        }

    }



}
