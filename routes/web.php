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


// Social Logins...
Route::get('/login/{social}','Auth\LoginController@socialLogin')
    ->where('social','facebook|github');

Route::get('/login/{social}/callback','Auth\LoginController@handleProviderCallback')
    ->where('social','facebook|github');

// Authentication Routes...
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/', 'Auth\LoginController@login');
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');



// App Routes...
Route::group(['middleware' => 'auth'], function () {
    Route::resource('contacts', 'ContactController', ['only' => ['index', 'destroy']]);
});



Horizon::auth(function ($request) {
    $user = \Auth()->user(); 
    if ($user && $user->email === 'michael_rice@live.com') {
        return true;
    }

    return abort(404, 'Page not found.');
});
