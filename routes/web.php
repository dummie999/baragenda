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

//agenda
Route::match(['get'], '/agenda', 'AgendaController@index')->name('agenda');
Route::match(['get'], '/agenda/edit', 'AgendaAdminController@edit')->name('agenda/edit');
Route::match(['post'], '/agenda/edit', 'AgendaAdminController@edit')->name('agenda/edit');


//user settings
Route::match(['get', 'post'], '/settings', 'UserSettingsController@changeSettings')->name('user.settings');

//management settings
Route::match(['get','post'], '/management', 'ManagementController@changeSettings')->name('management.settings');
Route::post( '/management/newRow/', 'ManagementController@newRow')->name('management.newRow');
Route::match(['get','post'],'/management/delRow/{shifttype}/', 'ManagementController@delRow')->name('management.delRow');

//shifts
Route::match(['get'], '/shifts', 'ShiftController@index')->name('shifts');
Route::match(['post'], '/shifts', 'ShiftController@UpdateShifts')->name('shifts');
Route::match(['post'], '/shiftsEnlist', 'ShiftController@enlist')->name('shifts.enlist');
Route::get('/shifts/page/{page}', 'ShiftController@index')->name('shifts.page');
Route::get('/shifts/{date}', 'ShiftController@openDate')->name('shifts.date');
Route::post('/shifts/{date}', 'ShiftController@removeUser')->name('shifts.removeUser');

//shiftmanagement
Route::match(['get','post'], '/shiftmanagement', 'ShiftAdminController@admin')->name('shifts.admin');
Route::get('/shiftmanagement/page/{page}', 'ShiftAdminController@admin')->name('shifts.admin.page');
Route::post('/shiftmanagement/page/{page}', 'ShiftAdminController@admin')->name('shifts.admin.page');