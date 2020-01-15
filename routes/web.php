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

Route::get('/tweets/create', 'TweetsController@create');

Route::get('/tweets/{id}', 'TweetsController@show');

Route::get('/tweets/{id}/edit', 'TweetsController@edit');

//ログイン状態
Route::group(['middleware' => 'auth'], function() {

    //ユーザ関連
    Route::resource('users', 'UsersController', ['only' => ['index', 'show', 'edit', 'updata']]);

    //ツイート関連
    Route::resource('tweets', 'TweetsController', ['only' => ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']]);
});
