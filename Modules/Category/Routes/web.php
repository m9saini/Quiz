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
    	Route::get('category/ajax/data', 'CategoryController@getAjaxData')->name('category.ajaxdata');
		Route::resource('category', 'CategoryController');
		//Upload Images By Ajax
    	Route::post('category/media/upload', 'CategoryController@saveMedia')->name('category.mediaStore');
    	Route::get('category/status/{slug}', 'CategoryController@status')->name('category.status');
	});
});
