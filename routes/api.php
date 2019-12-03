<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

$api = app('Dingo\Api\Routing\Router');
$api->version(['v1'],['namespace' => 'App\Http\Controllers\Api','middleware' =>'app_version'],  function ($api)
{
	//Get Details for Application version and domain which Api have run
    $api->get('check-version/{type}','ApiController@checkVersion');
    //Login Users With an Email ,Password and sucure apitoken which we provide directly
    $api->post('login','Auth\AuthenticationController@login');
    //Register Users With an Email ,Password and assign role dynamically
    $api->post('register','Auth\RegisterController@register');

    $api->post('loginwithsocial','Auth\SocialLoginController@socialLogin');

    $api->post('socialregister','Auth\SocialLoginController@register');

    $api->get('pages/{type}','ApiController@getPages');
  
});

$api->version(['v1'],['namespace' => 'App\Http\Controllers\Api','middleware' => 'client_credentials','app_version'],  function ($api)
{
  //Update User Profile Details
	//dd($api);
    $api->post('update/profile','User\UserController@updateProfile');   
    //USER KYC 
    $api->post('kyc/{slug}','ApiController@kycUpsert');
    $api->post('user-{slug}','ApiController@userFollowUpdate');
    $api->post('questions/{slug}','QuestionController@index');
    $api->post('account/{slug}','ApiController@userAccountUpsert');

});

