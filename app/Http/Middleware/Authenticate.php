<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if(!Auth::user()){ 
            if(\Request::segment(1) == 'admin'){  
                 return  route('admin.login');
            }
        }
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
