<?php

namespace App\Modules\System\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\System\SystemController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends SystemController
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
    protected $redirectTo = '/system';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    protected function guard()
    {
        return Auth::guard('staff');
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request)+['status'=>'active'], $request->has('remember')
        );
    }

    public function __construct(){
       $this->middleware('guest:staff')->except('logout');
    }
}
