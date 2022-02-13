<?php

use Illuminate\Http\Request;
use App\Http\Controllers;

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
Route::post('login', 'ApiController@login');
Route::post('register', 'ApiController@register');

Route::group(['middleware' => 'auth.jwt'], function () {
    Route::get('logout', 'ApiController@logout');

    // Pokemon list
    Route::get('pokemon', 'PokemonController@index');
    
    // Search pokemon by parameters
    Route::post('pokemon', 'PokemonController@show');

    // Send data to api coco
    Route::post('send_data', 'PokemonController@send_data');
});
