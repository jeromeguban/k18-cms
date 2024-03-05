<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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



Route::prefix('/users')->group( function(){

    Route::post('/login', 'api\LoginController@login');

});


Route::middleware('auth:api')->group( function(){
    
    Route::resource('/key-visual', 'KeyVisualController');
    
});


Route::middleware('auth:api')->group( function(){  
    Route::get('/user', 'Api\ActiveUserController@index');
    Route::patch('/update-credential', 'Api\CredentialController@update');  
});

// Route::middleware('auth:api')->group( function(){ 
    Route::post('/shipmates/callback', 'ShipmatesCallbackController@store');
// });


/*
|--------------------------------------------------------------------------
| Routes for Payment Gateway
|--------------------------------------------------------------------------
*/
Route::post('/payment-gateway/callback','PaymentGatewayCallbackController@store');
Route::post('/paymaya/callback','PaymayaCallbackController@store');
/*
|
*/

/*
|--------------------------------------------------------------------------
| Routes for Order
|--------------------------------------------------------------------------
*/
Route::post('/order','OrderController@store');
/*
|
*/


/*
|--------------------------------------------------------------------------
| Routes for Posting APi
|--------------------------------------------------------------------------
*/
Route::post('/postings','PostingController@store');
/*
|
*/


/*
|--------------------------------------------------------------------------
| Routes for Posting Item APi
|--------------------------------------------------------------------------
*/

Route::get('/klaviyo/catalog','KlaviyoCatalogController@index');

/*
|
*/

/*
|--------------------------------------------------------------------------
| Routes for  Google Map Address
|--------------------------------------------------------------------------
 */
Route::get('/google-address','GoogleMapAddressController@index');

/*
|
 */