<?php

namespace App\Http\Middleware\SanitizeResReq;

use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use Closure;
use Str;

class ResponseToCamelCase
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
        if ($request->isJson()) {
            $response = $next($request);

            if (!empty($response->getContent()))
                return response()->json(
                    $this->renameKeysToCamel(
                        json_decode($response->getContent())
                    ), $response->getStatusCode()
                );
            throw new MethodNotAllowedHttpException([]); 
        }
        return $next($request);
    }

    protected function renameKeysToCamel($response) {
        $newResponse = array();

        foreach($response as $key => $value) {
            if(is_string($key)) $key = Str::camel($key);
            if(is_object($value)) $value = $this->renameKeysToCamel($value);

            $newResponse[$key] = $value;
        }
        return $newResponse;
    }
}
