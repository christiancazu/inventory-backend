<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Controllers\API\ApiController;

use App\Models\User;
use App\Http\Resources\UserResource;

use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Requests\User\UpdateSelfUserRequest;

use HttpStatusCode;
use Hash;

class UserController extends ApiController
{
    protected $user;

    /**
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('staff', ['except' => ['updateSelf']]);

        $this->user = auth()->user();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): object
    {
        return User::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $userToUpdate = User::findOrFail($id);
        // can't update user with same role or major
        ($this->user->role_id >= $userToUpdate->role_id) && abort(HttpStatusCode::HTTP_FORBIDDEN);

        return tap(new UserResource($userToUpdate))->update($request->except(['password']));
    }

    public function updateSelf(UpdateSelfUserRequest $request)
    {
        $request->has('role_id') && abort(HttpStatusCode::HTTP_FORBIDDEN, 'resource.not_allowed.change_role');
        $request->has('activated') && abort(HttpStatusCode::HTTP_FORBIDDEN, 'resource.not_allowed.change_activated');

        switch (true) {
            case $this->user->isStaff():
                return $this->executeUpdate($request);

            default:
                // only pass as request 'password' & 'new_password'
                return $this->executeUpdate(
                    new \Illuminate\Http\Request($request->only(['password', 'new_password']))
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // id in request should be the same authenticated
    protected function isCurrentUserValid($id)
    {
        return ($id == $this->user->id);
    }

    protected function executeUpdate($request) {
        // if has password in request & is correct then set the new password
        if ($request->has('password')) {

            ! Hash::check($request['password'], $this->user->password) && abort(HttpStatusCode::HTTP_UNPROCESSABLE_ENTITY, 'validation.custom.password.incorrect');

            $request['password'] = bcrypt($request['new_password']);
        }

        return tap(new UserResource($this->user))->update($request->all());
    }
}
