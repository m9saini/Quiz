<?php
Route::group(['middleware' => ['guest']], function () {
    Route::get('admin/login', 'Auth\AdminLoginController@getAdminLogin')->name('admin.login');
    Route::post('admin/login', ['as'=>'admin.auth','uses'=>'Auth\AdminLoginController@adminAuth']);
});
//admin route for our multi-auth system
Route::prefix('admin')->group(function () {
    //admin password reset routes
    Route::post('/password/email','Auth\AdminForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('/password/reset','Auth\AdminForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('/password/reset','Auth\AdminResetPasswordController@reset');
    Route::get('/password/reset/{token}','Auth\AdminResetPasswordController@showResetForm')->name('admin.password.reset');
});

//Admin Backend route
Route::group(['namespace' => 'BackEnd', 'prefix' => 'admin', 'middleware' => 'web'], function () {
        Route::get('dashboard', 'DashboardController@index')->name('backend.dashboard');
        Route::get('/', 'DashboardController@index');

       //Roles Routing
        Route::get('role/ajax/data', 'RolesController@getAjaxData')->name('role.ajax.data');
	    Route::get('roles/restore', 'RolesController@restore')->name('roles.restore');
	    Route::get('roles/restore/status/{slug}', 'RolesController@RestoreRole')->name('roles.restore.back');
     	Route::delete('roles/delete/{slug}', 'RolesController@parmanent_destroy')->name('roles.delete');

	    Route::get('roles/permission/{slug}', 'RolesController@getPermission')->name('roles.premission.create');
     	Route::post('roles/permission/{slug}', 'RolesController@postPermission')->name('roles.premission.store');
	    Route::resource('roles', 'RolesController');

	    //Permission Ajax to get Data
     	Route::get('permission/ajax-data', 'PermissionController@getAjaxData')->name('permission.ajax.data');
     	Route::resource('permission', 'PermissionController');

        //User CURD Routing
        Route::get('users/ajax-data', 'UserController@getAjaxData')->name('users.ajaxdata');
        Route::get('deleted-users/ajax-data', 'UserController@getDeletedAjaxData')->name('deleted-users.ajax.data');
        Route::get('inactivated-users/ajax-data', 'UserController@getInactivatedAjaxData')->name('inactivated-users.ajax.data');
        Route::get('activated-users/ajax-data', 'UserController@getActivatedAjaxData')->name('activated-users.ajax.data');
        Route::get('users/status/{slug}', 'UserController@status')->name('users.status');
        Route::delete('users/delete/{slug}', 'UserController@parmanent_destroy')->name('users.directDelete');
        Route::get('users/deleted', 'UserController@deleted')->name('users.deleted');
        Route::get('users/activated', 'UserController@activated')->name('users.activated');
        Route::get('users/inactivated', 'UserController@inactivated')->name('users.inactivated');
        Route::resource('users', 'UserController');
        Route::get('users/list/{any}','UserController@index')->name('users.list');
        Route::get('users/restore/status/{slug}', 'UserController@RestoreUser')->name('users.restoreBack');
        Route::get('users/{role}/create', 'UserController@create')->name('users.userCreateByRole');
        Route::get('users/{role}/deleted', 'UserController@deleted')->name('users.deletedByRole');
        Route::get('users/{role}/inactivated', 'UserController@inactivated')->name('users.inactivatedByRole');
        Route::get('users/{role}/activated', 'UserController@activated')->name('users.activatedByRole');
        Route::get('users/{role}/show', 'UserController@show')->name('users.show');
        //Upload Images By Ajax
        Route::post('/user/media/upload', 'UserController@saveMedia')->name('media.store');

        Route::get('questions/ajax-data', 'QuestionController@getAjaxData')->name('questions.ajaxdata');
        Route::post('questions/ajax-option', 'QuestionController@getAjaxOption')->name('questions.ajaxoption');
        Route::get('questions/status/{id}', 'QuestionController@change_status')->name('questions.status');
        Route::post('questions/import', 'QuestionController@importQuestions')->name('questions.import');
        Route::resource('questions', 'QuestionController');
        Route::get('/settings', 'SettingController@index')->name('backend.settings');
        Route::post('/settings/store', 'SettingController@store')->name('settings.store'); 
        

});
?>
