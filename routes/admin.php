<?php

Route::view('/', 'admin');

Route::get('get/stats', 'GatheringController@getStatistics');

Route::get('get/{table}/table', 'TableController@get');
Route::get('set/{table}/table', 'TableController@set');
Route::get('get/{table}/tree', 'TreeController@get');

Route::view('/{any}', 'admin')->where('any', '.*');

//Route::get('admin/login', 'LoginController@get')->middleware('web');
//Route::post('admin/login', 'LoginController@post')->middleware('web');
//
//Route::middleware(config('sleeping_owl.middleware'))->prefix('admin')->group(function ()
//{
//    Route::get('dashboard', 'AdminController@dashboard')->middleware('web');
//
//    Route::group(['prefix' => 'notifications'], function ()
//    {
//        Route::get('get-all', 'NotificationController@getAll');
//        Route::get('get-unread', 'NotificationController@getFromLastReadNotificationIndex');
//        Route::get('update-last-read', 'NotificationController@updateLastReadNotification');
//    });
//});
