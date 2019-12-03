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

Route::prefix('admin')->group(function() {
	Route::middleware(['web'])->group(function () {
        Route::get('userskyc/ajax-data', 'UsersKycController@getAjaxData')->name('userskyc.ajaxdata');
		Route::resource('userskyc', 'UsersKycController');
		//Upload Images By Ajax
    	Route::get('userskyc/status/{slug}', 'UsersKycController@status')->name('userskyc.status');
    });
});
