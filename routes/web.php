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

/*
 * USER ENDPOINT
 * */
Route::get('/api/v1/users','UserController@get_all');
Route::post('/api/v1/users','UserController@create');
Route::get('/api/v1/users/{user_id}','UserController@get_by_id');
Route::put('/api/v1/users/{user_id}','UserController@update');

/*
 * ROLE ENDPOINT
 * */
Route::get('/api/v1/roles','RoleController@get_all');
Route::post('/api/v1/roles','RoleController@create');
Route::get('/api/v1/roles/{rol_id}','RoleController@get_by_id');
Route::put('/api/v1/roles/{role_id}','RoleController@update');
