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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('/index/refresh', ['as' => 'home.refresh', 'uses' => 'IndexController@indexRefresh']);

Route::group(['middleware' => ['auth:api']], function() {
    #PERSONAL CABINET
    Route::post('/chat/send', ['as' => 'chat.send', 'uses' => 'ChatController@send']);
    Route::post('/chat/rooms', ['as' => 'chat.rooms', 'uses' => 'ChatController@rooms']);
    Route::post('/chat/users', ['as' => 'chat.users', 'uses' => 'ChatController@users']);
    Route::post('/chat/refresh', ['as' => 'chat.refresh', 'uses' => 'ChatController@refresh']);
    Route::post('/chat/messages', ['as' => 'chat.messages', 'uses' => 'ChatController@messages']);
});

