<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\Auth;

use App\Http\Requests\Auth\SignInRequest;
use App\Http\Requests\Auth\SignUpRequest;

use App\Models\User;
use App\Http\Resources\UserResource;

use HttpStatusCode;

class AuthController extends ApiController
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $exceptMiddlewareList = ['signIn'];
        parent::__construct($exceptMiddlewareList);

        $this->middleware('superAdmin', ['only' => ['signUp']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(SignInRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (! $token = auth()->attempt($credentials)) {
            return SEND_ERROR(
                new class {
                    public $translate = 'validation.invalid_credentials';
                    public $httpStatusCode = HttpStatusCode::HTTP_UNAUTHORIZED;
                }
            );
        }
        return $this->respondWithToken($token);
    }

    /**
     * creating User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(SignUpRequest $request)
    {
        return User::create($request->all());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json($this->userResource());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut()
    {
        auth()->logout();

        return SEND_SUCCESS(new class {
            public $translate = 'auth.session.out';
        });
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'user' => $this->userResource(),
            'token' => $token,
            'expires' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function userResource()
    {
        // dd(\App\Role::find(3));
        // \App\Role::find(1)->restore();
        return new UserResource(auth()->user());
    }
}
