<?php
/**
 * Created by PhpStorm.
 * User: Tech2
 * Date: 9/7/2017
 * Time: 9:39 AM
 */

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Auth;
use Illuminate\Http\Request;

class StaffRole
{

    public function handle($request, Closure $next, $role){

        // Disabled Account
        if($request->user()->status == 'in-active'){
            Auth::logout();
            return redirect('/system/login');
        }

        // Prevent Duplicate Form Submission
        // if(in_array($request->method(),['POST','PATCH'])){
        //     try{
        //         $PMSmd5 = @md5(serialize($request->all()));
        //     }catch (\Exception $e){
        //         $PMSmd5 = @md5(date('Y-m-d H:i'));
        //     }
        //     if(session('PMSmd5') == $PMSmd5){
        //         abort(401, 'Can\'t Make Action At This Time');
        //     }
        //     $request->session()->put('PMSmd5',$PMSmd5);
        // }else{
        //     $request->session()->forget('PMSmd5');
        // }



        if(!$request->user()->permission_group){
            abort(401, 'Unauthorized.');
        }elseif(!empty($request->user()->permission_group->whitelist_ip)){

            $whitelist_ip = collect(explode("\n",$request->user()->permission_group->whitelist_ip))->map(function($value){
                return trim($value);
            })->reject(function($value){
                return empty($value);
            });

            $remoteIPAdds = getenv('HTTP_CF_CONNECTING_IP');
            if(!$remoteIPAdds){
                $remoteIPAdds = $request->ip();
            }

            if(!in_array($remoteIPAdds,$whitelist_ip->toArray())){
                abort(401, 'Unauthorized.');
            }
        }

        $ignoredRoutes = ['system.change-password','system.logout','system.dashboard','system.notifications.url','system.notifications.index','system.ajax.get','system.ajax.post','system.home-site'];


        $canAccess = array_merge($ignoredRoutes,Staff::StaffPerms($request->user()->id)->toArray());
        if (!in_array($role,$canAccess)){
            abort(401, 'Unauthorized.');
        }

        return $next($request);
    }

}