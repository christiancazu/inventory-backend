<?php

namespace App\Http\Middleware\Roles;

use Closure;

use HttpStatusCode;

class Staff
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
        return auth()->user()->isStaff()
            ? $next($request)
            : abort(HttpStatusCode::HTTP_FORBIDDEN, 'auth.session.not_permission');
    }
}
