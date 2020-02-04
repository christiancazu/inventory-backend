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
        $exceptAuthJWTMiddlewareList = ['signIn'];
        parent::__construct($exceptAuthJWTMiddlewareList);

        $this->middleware('staff', ['only' => ['signUp']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signIn(SignInRequest $request)
    {
        $credentials = $request->only('doc_num', 'password');

        // if credentials are incorrects -> 422
        ! ($token = auth()->attempt($credentials)) && abort(HttpStatusCode::HTTP_UNPROCESSABLE_ENTITY, 'validation.invalid_credentials');
    
        // if user->activated is false -> 403
        ! (bool) auth()->user()->activated && abort(HttpStatusCode::HTTP_FORBIDDEN, 'resource.not_allowed.access');

        return $this->respondWithToken($token);
    }

    /**
     * creating User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signUp(SignUpRequest $request)
    {
        // only accept a role_id major that selfone
        // ex: id of admin is 2 then can create users with role 3 or more
        ! ($request->role_id > auth()->user()->role_id) && abort(HttpStatusCode::HTTP_FORBIDDEN, 'resource.not_allowed.assign_role'); 

        // setting password equals to doc_num when a user is created for first time
        return User::create(
            $request->merge([
                'password' => bcrypt($request->doc_num)
            ])->all()
        );
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return SEND_RESPONSE($this->userResource());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function signOut()
    {
        auth()->logout();

        return SEND_RESPONSE(new class {
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
        return SEND_RESPONSE([
            'user' => $this->userResource(),
            'token' => $token,
            'expires' => auth()->factory()->getTTL() * 60
        ]);
    }

    protected function userResource()
    {
        return new UserResource(auth()->user());
    }
}
