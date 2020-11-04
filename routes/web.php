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

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

	Route::get('/admin', 'DashboardController@index')->name('admin');
    Route::get('/admin/users','UserController@index')->name('users');
    Route::get('/admin/users/edit/{id}','UserController@edit_user');
    Route::post('/admin/users/update', 'UserController@updateuser');
    Route::post('/admin/users/delete', 'UserController@delete');

    Route::get('/admin/colors','ColorController@index')->name('colors');
     Route::get('/admin/colors/edit/{id}','ColorController@edit_color');
     Route::post('/admin/colors/update', 'ColorController@updatecolor');
     Route::post('/admin/colors/delete', 'ColorController@delete');

     Route::get('/admin/colorsPalates','ColorController@colorsPalates');

   
});
Route::get('/home', 'HomeController@index')->name('home');



