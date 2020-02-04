<?php

namespace App\Http\Middleware\Roles;

use Closure;

use HttpStatusCode;

class SuperAdmin
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
        return auth()->user()->isSuperAdmin()
            ? $next($request)
            : abort(HttpStatusCode::HTTP_FORBIDDEN, 'auth.session.not_permission');
    }
}
