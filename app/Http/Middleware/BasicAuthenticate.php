<?php

namespace App\Http\Middleware;

use Closure;

class BasicAuthenticate
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
        $basic_auth = 'Basic VHViZUtpZHM6YWRtaW5UdWJlS2lkcw==';
        
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers : Content-type, X-Auth-Token, Authorization, Origin');

        $has_supplied_credentials = !(empty($_SERVER['HTTP_AUTHORIZATION']));
        $is_not_authenticated = (!$has_supplied_credentials ||
            $_SERVER['HTTP_AUTHORIZATION'] != $basic_auth);

        if ($is_not_authenticated) {
            return response()->json(['errors'=>array(['code'=> 401, 'message'=> 'Authorization Required'])], 401);
            exit;
        }
        return $next($request);
    }
}
