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

/*
 * AUTH ENDPOINT
 * */
Route::post('/v1/auth', 'AuthController@auth');
Route::get('/v1/users/verify/{verification_code}', 'UserController@verify_user');
Route::post('/v1/users', 'UserController@create');

Route::group(['middleware' => ['jwt.auth']], function () {
    /*
    * USER ENDPOINT
     * * */
    Route::get('/v1/users', 'UserController@get_all');
    Route::get('/v1/users', 'UserController@get_all');
    Route::get('/v1/users/{user_id}', 'UserController@get_by_id');
    Route::post('/v1/users/{user_id}/roles', 'UserController@add_roles');
    Route::put('/v1/users/{user_id}', 'UserController@update');


    /*
     * ROLE ENDPOINT
     * */
    Route::get('/v1/roles', 'RoleController@get_all');
    Route::post('/v1/roles', 'RoleController@create');
    Route::get('/v1/roles/{rol_id}', 'RoleController@get_by_id');
    Route::put('/v1/roles/{role_id}', 'RoleController@update');


    /*
     * COMPANY ENDPOINT
     * */
    Route::get('/v1/companies', 'CompanyController@get_all');
    Route::post('/v1/companies', 'CompanyController@create');
    Route::get('/v1/companies/{company_id}', 'CompanyController@get_by_id');
    Route::put('/v1/companies/{company_id}', 'CompanyController@update');

    /*
     * STATION USER ENDPOINT
     * */
    Route::get('/v1/station_users', 'StationUserController@get_all');
    Route::post('/v1/station_users', 'StationUserController@create');
    Route::get('/v1/station_users/{user_id}', 'StationUserController@get_by_id');
    Route::post('/v1/station_users/{user_id}/roles', 'StationUserController@add_roles');
    Route::put('/v1/station_users/{user_id}', 'StationUserController@update');


    /*
     * STATIONs ENDPOINT
     * */
    Route::get('/v1/stations', 'StationController@get_all');
    Route::post('/v1/stations', 'StationController@create');
    Route::get('/v1/stations/{station_id}', 'StationController@get_by_id');
    Route::put('/v1/stations/{station_id}', 'StationController@update');
    Route::get('/v1/stations', 'StationController@get_company_by_station');

    /*
     * TANK GROUPS ENDPOINT
     * */
    Route::get('/v1/tank_groups', 'TankGroupsController@get_all');
    Route::post('/v1/tank_groups', 'TankGroupsController@create');
    Route::get('/v1/tank_groups/{tank_id}', 'TankGroupsController@get_by_id');
    Route::put('/v1/tank_groups/{tank_id}', 'TankGroupsController@update');

    /*
     * PRODUCTS ENDPOINT
     * */
    Route::get('/v1/products', 'ProductsController@get_all');
    Route::post('/v1/products', 'ProductsController@create');
    Route::get('/v1/products/{product_id}', 'ProductsController@get_by_id');
    Route::put('/v1/products/{product_id}', 'ProductsController@update');

    /*
     * TANKS ENDPOINT
     * */
    Route::get('/v1/tanks', 'TanksController@get_all');
    Route::post('/v1/tanks', 'TanksController@create');
    Route::get('/v1/tanks/{tank_id}', 'TanksController@get_by_id');
    Route::put('/v1/tanks/{tank_id}', 'TanksController@update');

    /*
     * PUMP GROUPS ENDPOINT
     * */
    Route::get('/v1/pump_groups', 'PumpGroupsController@get_all');
    Route::post('/v1/pump_groups', 'PumpGroupsController@create');
    Route::get('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@get_by_id');
    Route::put('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@update');

    /*
     * PRODUCT PRICE CHANGE LOGS ENDPOINT
     * */
    Route::get('/v1/product_price_change', 'ProductPriceChangeLogsController@get_all');
    Route::post('/v1/product_price_change', 'ProductPriceChangeLogsController@create');
    Route::get('/v1/product_price_change/{product_price_change_id}', 'ProductPriceChangeLogsController@get_by_id');
    Route::put('/v1/product_price_change/{product_price_change_id}', 'ProductPriceChangeLogsController@update');

    /*
     * PUMPS ENDPOINT
     * */
    Route::get('/v1/pumps', 'PumpController@get_all');
    Route::post('/v1/pumps', 'PumpController@create');
    Route::get('/v1/pumps/{pump_id}', 'PumpController@get_by_id');
    Route::put('/v1/pumps/{pump_id}', 'PumpController@update');

    Route::get('/v1/test', function () {
        return response()->json(['foo' => 'bar']);
    });

    Route::get('/v1/test/token', 'CompanyController@get_token');

});