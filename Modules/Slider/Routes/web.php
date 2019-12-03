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
        Route::get('slider/ajax/data', 'SliderController@getAjaxData')->name('slider.ajaxdata');
		Route::resource('slider', 'SliderController');
		//Upload Images By Ajax
    	Route::post('slider/media/upload', 'SliderController@saveMedia')->name('slider.mediaStore');
    	Route::get('slider/status/{slug}', 'SliderController@status')->name('slider.status');
    });
});
