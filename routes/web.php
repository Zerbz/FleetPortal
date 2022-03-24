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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'DriverManagement@index')->name('home');

Route::get('/getUserId', 'MapController@getId')->name('getId');

Route::prefix('driver')->group(function() {
    Route::get('/login', 'Auth\DriverLoginController@showLoginForm')->name('driver.login');
    Route::post('/login', 'Auth\DriverLoginController@login')->name('driver.login.submit');
    Route::post('/logout', 'Auth\DriverLoginController@logout')->name('driver.logout');
    Route::get('/', 'DriverController@index')->name('driver.overview');
    Route::get('/checkin', 'DriverController@checkin')->name('driver.checkin');
    Route::get('/populate', 'DriverController@populate')->name('driver.populate');
    Route::get('/updateNotes', 'DriverController@updateNotes')->name('driver.updateNotes');
    Route::get('/populateByLoadId', 'DriverController@getLoadId')->name('driver.getLoad');
  });


Route::get('/home/modifyDriver/{id}', 'DriverManagement@fetchDriver');
Route::get('/home/deleteDriver/{id}', 'DriverManagement@deleteDriver');
Route::post('home/createDriver', 'DriverManagement@createDriver')->name('createDriver');
Route::post('home/updateDriver/{id}', 'DriverManagement@updateDriver')->name('updateDriver');

Route::get('/truck', 'TruckManagementController@index')->name('truck');
Route::get('/truck/modifyTruck/{id}', 'TruckManagementController@fetchTruck');
Route::get('/truck/deleteTruck/{id}', 'TruckManagementController@deleteTruck');
Route::post('truck/createTruck', 'TruckManagementController@createTruck')->name('createTruck');
Route::post('truck/updateTruck/{id}', 'TruckManagementController@updateTruck')->name('updateTruck');

Route::get('/loads', 'LoadController@index')->name('load');
Route::get('/loads/modifyLoad/{id}', 'LoadController@fetchLoad');
Route::get('/loads/deleteLoad/{id}', 'LoadController@deleteLoad');
Route::post('loads/createLoad', 'LoadController@createLoad')->name('createLoad');
Route::post('loads/updateLoad/{id}', 'LoadController@updateLoad')->name('updateLoad');
Route::get('/loads/fetchConfirmation/{id}', 'LoadController@fetchConfirmation');

Route::get('/reassign', 'ReassignmentController@index')->name('reassign');
Route::get('/reassign/fetchLoad/{id}', 'ReassignmentController@fetchLoad');
Route::post('/reassign/update/{id}', 'ReassignmentController@updateLoad');
Route::get('/reassign/remove/{id}', 'ReassignmentController@removeUnit');

Route::get('/service', 'ServiceController@index')->name('service');
Route::get('/service/fetchTruck/{id}', 'ServiceController@fetchTruck');
Route::post('/service/setService', 'ServiceController@setService')->name('setService');
Route::get('/service/removeService/{id}', 'ServiceController@removeService');

Route::get('/upload', 'UploadController@index')->name('upload');
Route::get('/upload/fetchLoad/{id}', 'UploadController@fetchLoad');
Route::post('/upload/uploadDocument/{id}', 'UploadController@uploadDocument');
Route::get('/upload/fetchCustoms/{id}', 'UploadController@fetchCustoms');
Route::get('/upload/fetchManifest/{id}', 'UploadController@fetchManifest');

Route::get('/overview', 'HomeController@index');
Route::get('/overview/filter', 'HomeController@filter');
Route::get('/overview/display', 'HomeController@displayLoad');
Route::get('/overview/displayRoute', 'MapController@displayRoute');
Route::post('/overview/storeRoute', 'MapController@storeRoute');
Route::get('/overview/populate', 'HomeController@populateByType');
Route::get('/overview/populateById', 'HomeController@populateById');


Route::get('/authorize/{key}', 'Auth\RegisterController@authorizeUser');   
Route::get('/profile', 'ProfileController@index')->name('user_profile');
Route::post('/profile/update', 'ProfileController@update')->name('update_profile');