<?php

namespace App\Http\Middleware;

use App\Modules\Api\User\MerchantsApiController;
use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class Language
{
    public function handle($request, Closure $next)
    {
        if(Auth::check()) {
            if((isset($request->lang)) && (in_array($request->lang,['ar','en']))){
                if($request->lang != Auth::user()->language_key){
                    Auth::user()->update(['language_key'=>$request->lang]);
                }
                App::setLocale($request->lang);
            } else {
                App::setLocale(Auth::user()->language_key);
            }

        } else {
            App::setLocale('ar');
        }

        return $next($request);
    }
}