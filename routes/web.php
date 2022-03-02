<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'message'], function(){
    Route::post('create', 'MessageController@create');
    Route::get('delete', 'MessageController@delete');
});

Route::group(['prefix' => 'chat'], function(){
    Route::get('/', 'RoomController@index')->middleware('auth');
    Route::delete('delete', 'RoomController@delete');
    Route::post('room', 'RoomController@create');
    Route::get('{id}', 'RoomController@show')->middleware('auth');
});
