<?php

use App\Http\Middleware\AuthJwt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([AuthJwt::class])->group(function () {
    Route::post('auth', 'MainController@getToken')->withoutMiddleware(AuthJwt::class);
    Route::resource('cities', 'Rest\CityController')->middleware('role:anonymous');
    Route::resource('cities.streets', 'Rest\StreetController');
    Route::resource('cities.streets.buildings', 'Rest\BuildingController');
});
