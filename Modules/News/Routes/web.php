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

// Route::prefix('news')->group(function() {
//     Route::get('/', 'NewsController@index');
// });

Route::prefix('admin')->group( function() {
	Route::middleware(['web'])->group(function () {
        
		Route::resource('news', 'NewsController');
		//Route::post('news/deleteAjax/{slug}', 'NewsController@destory')->name('news.deleteAjax');
		//Upload Images By Ajax
    	Route::post('news/media/upload', 'NewsController@saveMedia')->name('news.mediaStore');
    	
    	
    });
});