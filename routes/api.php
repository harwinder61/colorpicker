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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@authenticate');
Route::post('forget_pswd','Api\UserController@forgot_pswd');
Route::post('social_login', 'Api\UserController@social_login');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user_detail', 'Api\UserController@getAuthenticatedUser');
    Route::post('edit_profile','Api\UserController@update_user_profile');
    Route::post('image_upload','Api\UserController@upload_image');
    Route::post('color','Api\ColorController@add_color');
    Route::post('allcolor','Api\ColorController@getallcolor');
    Route::post('updateColor','Api\ColorController@updateColor');
    Route::post('deleteColor','Api\ColorController@deleteColor');
    Route::post('ColorPalates','Api\ColorController@ColorPalates');
    Route::post('DeleteColorPalates','Api\ColorController@DeleteColorPalates');
    Route::post('GetColorPalates','Api\ColorController@GetColorPalates');
    Route::post('UpdateColorPalates','Api\ColorController@UpdateColorPalates');
   
});
