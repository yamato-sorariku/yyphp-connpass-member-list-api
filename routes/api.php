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

Route::namespace('Apis')->name('api.')->group(function () {
    Route::get('events', 'EventController@events')->name('events');
    Route::get('events/{event_id}', 'EventController@eventDetail')->name('eventDetail');
});