<?php

namespace App\Modules\Merchant\Auth;

use Illuminate\Support\Facades\Auth;
use App\Modules\Merchant\MerchantController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LoginController extends MerchantController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;




    function showLoginForm(){
        return parent::view('auth.login');
    }
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/merchant';

    public function login(Request $request) {
        //validate the form data
        $this->validate($request,[
            'id' => 'required|numeric',
            'password' => 'required|min:6'
        ]);
        if(
            (Auth::guard('merchant_staff')->attempt(['id' => $request->id, 'password' => $request->password, 'status'=>'active'], $request->remember))
            && ((Auth('merchant_staff')->check()) && (Auth('merchant_staff')->user()->merchant()->status=='active' && Auth('merchant_staff')->user()->status=='active'))
        ){
            //if successful redirect to admin dashboard
            return redirect()->intended(route('panel.merchant.home'));
        }
        return back()
            ->withInput($request->only('id','remember'))
            ->with('msg',__('Wrong username or password'));
    }


    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected function guard()
    {
        return Auth::guard('merchant_staff');
    }

    public function __construct(){
        $this->middleware('guest:merchant_staff')->except('logout');
    }

    protected function logout(Request $request){
        $this->guard()->logout();

        //$request->session()->invalidate();
        return redirect('/merchant/login');
    }
}
