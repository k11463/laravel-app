<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
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
        // return $next($request)->header('Access-Control-Allow-Origin', '*')
        //                         ->header('Access-Control-Allow-Methods', '*')
        //                         ->header('Access-Control-Allow-Headers', 'Origin, Methods, Content-Type, Authorization')
        //                         ->header('Access-Control-Allow-Credentials', true);
        
        $host = $request->getHost();
        $domains = ['localhost:8080', 'tsushansite.ddns.net:80'];

        if ( isset( $request->server()['HTTP_ORIGIN'] ) ) {
            $origin = $request->server()['HTTP_ORIGIN'];
        
            $pattern = "";
            if (preg_match('#^https?://#', $origin)) {
                $pattern = preg_replace('#^https?://#', '', $origin);
            }
            if ( in_array( $pattern, $domains ) ) {
            
            return $next($request)
                ->header('Access-Control-Allow-Origin', $origin)
                ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization')
                ->header('Access-Control-Allow-Methods', 'PUT, GET, POST, DELETE, OPTIONS');
            }
        }
    }
}
