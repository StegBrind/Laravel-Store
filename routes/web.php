<?php

use Illuminate\Support\Facades\Auth;
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

Route::post('image/upload', 'ImageController@upload');

Route::get('admin', 'Admin\LoginController@get');
Route::post('admin', 'Admin\LoginController@post');

Route::group(['prefix' => 'registration'], function()
{
    Route::get('/', 'Auth\RegisterController@getRegistrationPage');
    Route::post('/', 'Auth\RegisterController@register');
});

Route::get('/', 'MainPageController@get');
Route::post('/', 'MainPageController@post');

Route::group(['prefix' => 'category'], function ()
{
    Route::get('/live/search', 'CategoryController@search');
    Route::get('/{id}', 'CategoryController@show');
});

Route::group(['prefix' => 'conversation', 'middleware' => ['web', 'auth']], function ()
{
    Route::get('talk/{user_id}', 'ConversationController@talk');
    Route::get('content/{user_ids}/{file_name}', 'ConversationController@getContent');
    Route::get('list', 'ConversationController@showList');
});


Route::group(['prefix' => 'login'], function ()
{
    Route::get('/', 'Auth\LoginController@showLoginForm');
    Route::post('/', 'Auth\LoginController@login');
});

Auth::routes();
Auth::routes(['verify' => true]);
Route::view('verify', 'auth.verify');

Route::group(['prefix' => 'forgot_password'], function ()
{
    Route::view('/', 'auth.forgot_password');
    Route::post('/', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('resetting', 'Auth\ResetPasswordController@reset');
});

Route::view('successful', 'successful');