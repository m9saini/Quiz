<?php

namespace App\Http\Middleware;

use Closure;

class ChackAppVersion
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $device_type = $request->headers->get('deviceType');
        $reqVersion = $request->headers->get('version');
        if ($device_type && $reqVersion) {
            return $next($request);
        } else {
            throw new \Dingo\Api\Exception\StoreResourceFailedException('Device type and version are required.', []);
        }
    }
}
