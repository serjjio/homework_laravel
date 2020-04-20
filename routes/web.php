<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/max_pk', 'MainController@getList');
Route::get('/address/streets', 'AddressController@getListStreets');
Route::get('/address/streets/{name}', 'AddressController@getStreetByName');
Route::get('/address/city/{name}', 'AddressController@getCityByName');
