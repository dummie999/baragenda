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

//management settings
Route::match(['get','post'], '/management', 'ManagementController@changeSettings')->name('management.settings');
Route::post( '/management/newRow/', 'ManagementController@newRow')->name('management.newRow');
Route::match(['get','post'],'/management/delRow/{shifttype}/', 'ManagementController@delRow')->name('management.delRow');

//shifts
Route::match(['get'], '/shifts', 'ShiftController@index')->name('shifts');
Route::match(['post'], '/shifts', 'ShiftController@UpdateShifts')->name('shifts');
Route::get('/shifts/page/{page}', 'ShiftController@index')->name('shifts.page');
Route::get('/shifts/{date}', 'ShiftController@openDate')->name('shifts.date');

//shiftmanagement
Route::match(['get','post'], '/shiftmanagement', 'ShiftController@admin')->name('shifts.admin');
