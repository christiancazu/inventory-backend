<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct(array $exceptAuthJWTMiddlewareList = [])
    {
        $this->middleware('authJWT', ['except' => $exceptAuthJWTMiddlewareList]); 
    }
}
