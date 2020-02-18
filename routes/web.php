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

Route::get('/', 'HomeController@index')->name('home');

//login
Route::get('login/{token}', 'LoginController@loginByToken');
Route::match(['get', 'post'], '/login', 'LoginController@login')->name('login');
Route::post('/logout', 'LoginController@logout')->name('logout');

//user settings
Route::match(['get', 'post'], '/settings', 'UserSettingsController@changeSettings')->name('user.settings');

//shifts 
Route::match(['get'], '/shifts', 'ShiftController@index')->name('shifts');
Route::match(['post'], '/shifts', 'ShiftController@UpdateShifts')->name('shifts');
Route::get('/page/{page}', 'ShiftController@index')->name('shifts.page');