<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*
 █████╗ ██╗   ██╗████████╗██╗  ██╗
██╔══██╗██║   ██║╚══██╔══╝██║  ██║
███████║██║   ██║   ██║   ███████║
██╔══██║██║   ██║   ██║   ██╔══██║
██║  ██║╚██████╔╝   ██║   ██║  ██║
╚═╝  ╚═╝ ╚═════╝    ╚═╝   ╚═╝  ╚═╝
*/
Route::group([

    'prefix' => 'auth'

], function () {
    Route::post('signIn', 'AuthController@signIn');
    Route::post('signUp', 'AuthController@signUp');
    Route::get('signOut', 'AuthController@signOut');
    Route::get('refresh', 'AuthController@refresh');
    Route::get('user', 'AuthController@user');
});

/*
██████╗  ██████╗ ██╗     ███████╗███████╗
██╔══██╗██╔═══██╗██║     ██╔════╝██╔════╝
██████╔╝██║   ██║██║     █████╗  ███████╗
██╔══██╗██║   ██║██║     ██╔══╝  ╚════██║
██║  ██║╚██████╔╝███████╗███████╗███████║
╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝
*/
Route::group([

], function () {
    Route::apiResource('roles', 'RoleController');
});

/*
██╗   ██╗███████╗███████╗██████╗ ███████╗
██║   ██║██╔════╝██╔════╝██╔══██╗██╔════╝
██║   ██║███████╗█████╗  ██████╔╝███████╗
██║   ██║╚════██║██╔══╝  ██╔══██╗╚════██║
╚██████╔╝███████║███████╗██║  ██║███████║
 ╚═════╝ ╚══════╝╚══════╝╚═╝  ╚═╝╚══════╝
*/
Route::group([

], function () {
    Route::apiResource('users', 'UserController');
    Route::put('users', 'UserController@updateSelf');
});
