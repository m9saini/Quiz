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
Route::prefix('admin')->group( function() {
	Route::middleware(['web'])->group(function () {
        Route::get('staticpages/ajax/data', 'StaticPagesController@getAjaxData')->name('staticpages.ajaxdata');
		Route::resource('staticpages', 'StaticPagesController');
		//Upload Images By Ajax
    	Route::post('staticpages/media/upload', 'SliderController@saveMedia')->name('staticpages.mediaStore');
    	Route::get('staticpages/status/{slug}', 'SliderController@status')->name('staticpages.status');
    });
});