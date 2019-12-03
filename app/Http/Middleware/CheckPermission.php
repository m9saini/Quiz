<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = \Request::route()->getName(); 
        $pathAccess =  \Entrust::can($path);   
        $superAdmin =  \Entrust::hasRole('admin');
        if(!Auth::user()){
             if(\Request::segment(1) == 'admin'){
                 return  redirect()->route('admin.login');
            }
        }
        if(!$superAdmin)
        {
            if(is_array($request) && in_array($path, $request))
                return $next($request);

            if($pathAccess)
                return $next($request);

            return redirect()->route('home');
        }
        else
        {
            return $next($request);
        }
                
    }
}