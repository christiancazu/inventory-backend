<?php

namespace App\Http\Middleware\SanitizeResReq;

use Closure;
use Str;

class RequestToSnakeCase
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
        if ($request->isJson())
        {
            // Fetch json data
            $newRequest = $this->renameKeysToSnake($request->json()->all());
            // Create changed request
            $request->json()->replace($newRequest);
        }
        return $next($request);
    }

    protected function renameKeysToSnake($request) {
        $newRequest = array();

        foreach($request as $key => $value) 
        {
            if(is_string($key)) $key = Str::snake($key);
            if(is_array($value)) $value = $this->renameKeysToSnake($value);

            $newRequest[$key] = $value;
        }
        return $newRequest;
    }
}
