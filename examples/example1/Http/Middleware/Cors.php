<?php

namespace App\Http\Middleware;

use Closure;

class Cors {

    public function handle($request, Closure $next) {

        $response = $next($request);
        $response->headers->set("Access-Control-Allow-Origin", "http://ak.at.kg");
        $response->headers->set("Access-Control-Allow-Credentials", "true");
        $response->headers->set("Access-Control-Allow-Methods", "GET,HEAD,OPTIONS,POST,PUT");
        $response->headers->set("Access-Control-Allow-Headers", "Access-Control-Allow-Headers,xsrf-token, x-xsrf-token,Access-Control-Allow-Origin, Origin,Accept, X-Requested-With, Content-Type, Access-Control-Request-Method, Access-Control-Request-Headers");
        return $response;
    }
}
