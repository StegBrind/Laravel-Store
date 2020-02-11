<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'login'], function ()
{
    Route::view('view', 'auth.login');
    Route::post('/', 'Auth\LoginController@login');
});

Route::group(['prefix' => 'category'], function ()
{
    Route::get('{id}/view', 'CategoryController@show');
    Route::get('live/search', 'CategoryController@search');
});

Route::group(['prefix' => 'index'], function ()
{
    Route::post('post', 'MainPageController@post');
    Route::view('view', 'index');
});
