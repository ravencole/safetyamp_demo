<?php

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\User as UserResource;

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

Route::get('', function() {
  return response()->json([]);
});

Route::get('users', function() {
  return UserResource::collection(User::with(['department'])->get());
});

Route::middleware('auth:api')->group(function () {
  // Users
  Route::get('me', function() {
    return response()->json([
      'data' => (new UserResource(Auth::user()))
    ]);
  });

  // JHAs
  Route::get('me/jhas', 'MyJHAsController@index');
  Route::get('jhas', 'JHAsController@index');
  Route::post('jhas', 'JHAsController@store');
  Route::put('jhas/{jha}', 'JHAsController@update');
  Route::delete('jhas/{jha}', 'JHAsController@destroy');
  Route::post('jhas/{jha}/approve', 'JHAApprovalController@store');
  Route::post('jhas/{jha}/review', 'JHAReviewController@store');

  // Departments
  Route::get('departments', 'DepartmentController@index');
  Route::get('departments/{department}', 'DepartmentController@show');
  Route::get('departments/{department}/jhas', 'DepartmentJHAsController@index');
});


