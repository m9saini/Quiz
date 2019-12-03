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

Route::group(
[
	'prefix' => LaravelLocalization::setLocale(),
	'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
],
function()
{ 
	/** ADD ALL LOCALIZED ROUTES INSIDE THIS GROUP **/
	Route::get('/', function () {
		if(!Schema::hasTable('users')){
			return redirect('install');
		}


	    return view('welcome');

	});
	Route::get('/', 'Auth\AdminLoginController@getAdminLogin')->name('admin.login');
	//Route translate middleware
	Route::get(LaravelLocalization::transRoute('routes.home'), 'HomeController@index')->name('home');

});

/*
|--------------------------------------------------------------------------
| Social Routes
|--------------------------------------------------------------------------
|
*/
Route::group(['prefix' => 'login'], function()
{ 
    Route::get('facebook', 'Auth\LoginController@redirectToProvider')->name('login.facebook');
    Route::get('{providerd}/callback', 'Auth\LoginController@handleProviderCallback')->name('social.callback');
    Route::get('google', 'Auth\LoginController@redirectToProviderGoogle')->name('login.google');

    Route::get('facebook-linked', 'Auth\LoginController@redirectToProviderLinked')->name('linked.facebook');
     //Linked By social for User Accounts
    Route::get('{providerd}/linkedcallback', 'Auth\LoginController@handleProviderLinkedCallback')->name('social.linkedCallback');
    Route::get('google-linked', 'Auth\LoginController@redirectToProviderGoogleLinked')->name('linked.google');
});

Auth::routes(['verify' => true]);


