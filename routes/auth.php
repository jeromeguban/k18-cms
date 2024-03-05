<?php

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
Route::get('/', function () {
    return redirect('login');
});

Route::get('login', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('settings/store', function () {

    if (session()->get('store_id')) {
        return redirect('/dashboard');
    }

    return view('settings.store');

})->middleware(['auth']);

Route::post('stores/{store}/validate', 'StoreValidateController@store');

Route::delete('store-validation', 'StoreValidateController@destroy');

/*
|--------------------------------------------------------------------------
| Forgot Password Routes
|--------------------------------------------------------------------------
 */

Route::get('forgot-password', 'ForgotPasswordController@index');

Route::post('forgot-password', 'ForgotPasswordController@sendResetLinkEmail');

/*
|--------------------------------------------------------------------------
| Reset Pssword Routes
|--------------------------------------------------------------------------
 */

Route::post('user/password-reset', 'UserResetLinkController@update');

Route::get('user/password-reset/{token}', 'UserResetLinkController@show');
