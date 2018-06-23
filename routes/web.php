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

/*Route::get('/', function () {
    return view('welcome');
});*/

/*Route::get('/otp', function () {
    return view('auth.otp');
});*/

Auth::routes();

Route::match(['get','post'],'/otp', 'Auth\LoginController@otp')->name('otp');

Route::get('/', 'HomeController@index')->name('home');
Route::get('/post/{post}', 'HomeController@post')->name('post');
Route::get('/edit-profile/{user}', 'HomeController@editProfile')->name('edit-profile')->middleware('auth');
Route::post('/update-profile/{user}', 'HomeController@updateProfile')->name('update-profile')->middleware('auth');

Route::resource('posts','PostController')->middleware('auth');
