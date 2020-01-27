<?php

namespace App\Http\Middleware\Roles;

use Closure;

use HttpStatusCode;

class Admin
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
        return auth()->user()->isAdmin()
            ? $next($request)
            : SEND_RESPONSE(new class {
                public $translate = 'auth.session.not_permission';
                public $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED;
            });
    }
}
