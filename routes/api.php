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
    Route::get('/v1/roles/by_company/{rol_id}', 'RoleController@get_by_company_id');
    Route::put('/v1/roles/{role_id}', 'RoleController@update');

    /*
     * Permissions ENDPOINT
     * */
    Route::get('/v1/permissions', 'PermissionController@get_all');
    Route::post('/v1/permissions', 'PermissionController@create');
    Route::get('/v1/permissions/{rol_id}', 'PermissionController@get_by_id');
    Route::get('/v1/permissions/by_company/{rol_id}', 'PermissionController@get_by_company_id');
    Route::put('/v1/permissions/{role_id}', 'PermissionController@update');


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
    Route::get('/v1/company_users', 'CompanyUserController@get_all');
    Route::post('/v1/company_users', 'CompanyUserController@create');
    Route::get('/v1/company_users/{user_id}', 'CompanyUserController@get_by_id');
    Route::get('/v1/company_users/by_company/{company_id}', 'CompanyUserController@get_by_company_id');
    Route::post('/v1/company_users/{user_id}/roles', 'CompanyUserController@add_roles');
    Route::put('/v1/company_users/{user_id}', 'CompanyUserController@update');


    /*
     * STATIONs ENDPOINT
     * */
    Route::get('/v1/stations', 'StationController@get_all');
    Route::post('/v1/stations', 'StationController@create');
    Route::get('/v1/stations/{station_id}', 'StationController@get_by_id');
    Route::patch('/v1/stations/{station_id}', 'StationController@update');
    Route::get('/v1/stations', 'StationController@get_company_by_station');
    Route::get('/v1/stations/by_company/{company_id}', 'StationController@get_stations_by_company_id');

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
    Route::patch('/v1/tanks/{tank_id}', 'TanksController@update');
    Route::get('/v1/tanks/by_station/{station_id}', 'TanksController@get_tanks_by_station_id');


    /*
     * PUMP GROUPS ENDPOINT
     * */
    Route::get('/v1/pump_groups', 'PumpGroupsController@get_all');
    Route::post('/v1/pump_groups', 'PumpGroupsController@create');
    Route::get('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@get_by_id');
    Route::put('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@update');

    /*
     * PRODUCT PRICE  ENDPOINT
     * */
    Route::get('/v1/product_price', 'ProductPriceController@get_all');
    Route::post('/v1/product_price', 'ProductPriceController@create');
    Route::get('/v1/product_price/{product_price}', 'ProductPriceController@get_by_id');
     Route::get('/v1/product_price/by_station/{station_id}', 'ProductPriceController@get_by_station_id');
    Route::put('/v1/product_price/{product_price}', 'ProductPriceController@update');
    /*
     * PRODUCT PRICE CHANGE LOGS ENDPOINT
     * */
    Route::get('/v1/product_price_change', 'ProductPriceChangeLogsController@get_all');
    Route::post('/v1/product_price_change', 'ProductPriceChangeLogsController@create');
    Route::get('/v1/product_price_change/{product_price_change_id}', 'ProductPriceChangeLogsController@get_by_id');
     Route::get('/v1/product_price_change/by_station/{product_price_change_id}', 'ProductPriceChangeLogsController@get_by_station_id');
    Route::put('/v1/product_price_change/{product_price_change_id}', 'ProductPriceChangeLogsController@update');

    /*
     * PUMPS ENDPOINT
     * */
    Route::get('/v1/pumps', 'PumpController@get_all');
    Route::post('/v1/pumps', 'PumpController@create');
    Route::get('/v1/pumps/{pump_id}', 'PumpController@get_by_id');
    Route::get('/v1/pumps/by_station/{station_id}', 'PumpController@get_by_station_id');
    Route::patch('/v1/pumps/{pump_id}', 'PumpController@update');



     /*
     * PUMPS to Tanks ENDPOINT
     * */
    Route::get('/v1/pumps-tanks', 'PumpGroupToTankGroupController@get_all');
    Route::post('/v1/pumps-tanks', 'PumpGroupToTankGroupController@create');
    Route::get('/v1/pumps-tanks/{pump_id}', 'PumpGroupToTankGroupController@get_by_id');
    Route::get('/v1/pumps-tanks/by_station/{station_id}', 'PumpGroupToTankGroupController@get_by_station_id');
    Route::patch('/v1/pumps-tanks/{pump_id}', 'PumpGroupToTankGroupController@update');

    /*
     * Totalizers READINGS ENDPOINT
     * */
    Route::get('/v1/totalizer-readings', 'DailyTotalizersReadingsController@get_all');
    Route::post('/v1/totalizer-readings', 'DailyTotalizersReadingsController@create');
    Route::get('/v1/totalizer-readings/{pump_id}', 'DailyTotalizersReadingsController@get_by_id');
    Route::get('/v1/totalizer-readings/by_station/{station_id}', 'DailyTotalizersReadingsController@get_by_station_id');
    Route::patch('/v1/totalizer-readings/{pump_id}', 'DailyTotalizersReadingsController@update');

    /*
     * Stock READINGS ENDPOINT
     * */
    Route::get('/v1/stock-readings', 'DailyStockReadingsController@get_all');
    Route::post('/v1/stock-readings', 'DailyStockReadingsController@create');
    Route::get('/v1/stock-readings/{params}', 'DailyStockReadingsController@get_by_params');
    //Route::get('/v1/stock-readings/by_station', 'DailyStockReadingsController@get_by_station_id');
    Route::patch('/v1/stock-readings/{pump_id}', 'DailyStockReadingsController@update');

     /*
     * TOTALIZERS READINGS ENDPOINT
     * */
    Route::get('/v1/pump-readings', 'DailyTotalizersReadingsController@get_all');
    Route::post('/v1/pump-readings', 'DailyTotalizersReadingsController@create');
    Route::get('/v1/pump-readings/{pump_id}', 'DailyTotalizersReadingsController@get_by_params');
    //Route::get('/v1/pump-readings/by_station', 'DailyTotalizersReadingsController@get_by_station_id');
    Route::patch('/v1/pump-readings', 'DailyTotalizersReadingsController@update');

    Route::get('/v1/test', function () {
        return response()->json(['foo' => 'bar']);
    });

    Route::get('/v1/test/token', 'CompanyController@get_token');

});