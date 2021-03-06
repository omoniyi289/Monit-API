<?php

use Illuminate\Http\Request;

/*
     *SM ENDPOINT EXPOSED TO VELOX
     * */
Route::get('/v1/velox_product_prices', 'VeloxProductPriceController@get_by_params');
Route::get('/v1/velox_pumps', 'VeloxPumpController@get_by_params');
Route::get('/v1/velox_fcc_transactions', 'FCCTransactionController@get_by_params');



/*
 * V1-V2 MIGRATION ENDPOINT ENDPOINT
 * */
Route::get('/v1/update_demo_readings', 'MigrationController@get_up_to_date_readings_of_a_reliable_station_for_demo');
Route::get('/v1/get_sales_stock', 'MigrationController@get_up_to_sales_stock_for_demo_station');

Route::get('/v1/company_mg', 'MigrationController@company_migrate');
Route::get('/v1/user_mg', 'MigrationController@user_migrate');
Route::get('/v1/station_mg', 'MigrationController@station_migrate');
Route::get('/v1/role_mg', 'MigrationController@role_migrate');
Route::get('/v1/raw_tls_all', 'TLSController@get_raw');
Route::get('/v1/raw_tls_today', 'TLSController@get_today');
Route::get('/v1/user_role_mg', 'MigrationController@user_role_migrate');
Route::get('/v1/role_perm_mg', 'MigrationController@role_perm_migrate');
Route::get('/v1/user_station_mg', 'MigrationController@user_station_migrate');
Route::get('/v1/user_notf_mg', 'MigrationController@user_notf_migrate');


Route::get('/v1/pump_mg', 'MigrationController@pump_migrate');
Route::get('/v1/tank_mg', 'MigrationController@tank_migrate');
Route::get('/v1/tankgroup_mg', 'MigrationController@tankgroup_migrate');
Route::get('/v1/pumpgroup_mg', 'MigrationController@pumpgroup_migrate');
Route::get('/v1/p_t_map_mg', 'MigrationController@p_t_map_migrate');

Route::get('/v1/preadings_mg', 'MigrationController@preadings_migrate');
Route::get('/v1/treadings_mg', 'MigrationController@treadings_migrate');

Route::get('/v1/preadings_update_mg', 'MigrationController@preadings_update_migrate');
Route::get('/v1/treadings_update_mg', 'MigrationController@treadings_update_migrate');

Route::get('/v1/ptt_product_mg', 'MigrationController@ptt_product_migrate');
Route::get('/v1/ptp_product_mg', 'MigrationController@ptp_product_migrate');
Route::get('/v1/pp_mg', 'MigrationController@pp_migrate');
Route::get('/v1/pplog_mg', 'MigrationController@pplog_migrate');

Route::get('/v1/deposits_mg', 'MigrationController@deposits_migrate');
Route::get('/v1/expense_header_mg', 'MigrationController@expense_header_migrate');
Route::get('/v1/expense_items_mg', 'MigrationController@expense_items_migrate');

/*
 * V1-V2 FG DEMO MIGRATION ENDPOINT ENDPOINT
 * */
Route::get('/v1/demo_company_mg', 'FGDemoMigrationController@company_migrate');
Route::get('/v1/demo_station_mg', 'FGDemoMigrationController@station_migrate');

Route::get('/v1/demo_preadings_mg', 'FGDemoMigrationController@preadings_migrate');
Route::get('/v1/demo_treadings_mg', 'FGDemoMigrationController@treadings_migrate');

Route::get('/v1/demo_pt1_product_mg', 'FGDemoMigrationController@pt1_product_migrate');
Route::get('/v1/demo_pt2_product_mg', 'FGDemoMigrationController@pt2_product_migrate');

Route::get('/v1/pm_setter', '\App\Initializers\CompanyPermissionAndNofiticationSetter@pm_setter');
Route::get('/v1/unc_setter', '\App\Initializers\UserNotfCompanyIdSetter@unc_setter');



/*
 * OFF-AUTH MIDDLEWARE ENDPOINT
 * */
Route::get('/v1/uwp', 'UserController@users_with_default_password');
Route::post('/v1/auth', 'AuthController@auth');
Route::post('/v1/analytics_login', 'AuthController@analytics_login');
Route::post('/v1/sm_redirect_login', 'AuthController@sm_redirect_analytics_login');
Route::post('/v1/ecas_login', 'AuthController@ecas_login');
Route::post('/v1/auth/forgotpass/verifyemail', 'AuthController@passwordreset');
Route::get('/v1/users/verify/{verification_code}', 'UserController@verify_user');
Route::post('/v1/users', 'UserController@create');
Route::post('/v1/fromMail_fuel-supply', 'FromMail_FuelSupplyController@update');
Route::post('/v1/fromMail_pricing', 'FromMail_PriceChangeController@update');
Route::get('/v1/paga/connect', 'PagaConnectController@connect');
Route::get('/v1/paga/live/connect', 'PagaBusinessConnectController@connect');
Route::get('/v1/stock-received/print-delivery-pdf', 'StockReceivedController@get_delivery_pdf');
Route::get('/v1/fuel-supply/print-waybill-pdf', 'StockReceivedController@get_waybill_pdf');
Route::get('/v1/stock-readings/get-template-csv', 'DailyStockReadingsController@get_template_csv');
Route::get('/v1/totalizer-readings/get-template-csv', 'DailyTotalizersReadingsController@get_template_csv');
Route::get('/v1/roles/permissions/{role_id}', 'RoleController@get_role_permissions');
Route::get('/v1/fuel-supply/autorequest', 'FuelSupplyController@autorequest');

///ng demo 
Route::post('/v1/demo_dashboard', 'FGDemoController@get_dashboard_kpis');
Route::post('/v1/station_delivery', 'FGDemoController@add_station_delivery');
Route::post('/v1/demo_station_replenishment_plan', 'FGDemoController@get_demo_station_replenishment_plan');

Route::group(['middleware' => ['jwt.auth']], function () {
    /*
    * USER ENDPOINT
     * * */
    Route::get('/v1/users', 'UserController@get_by_params');
    Route::get('/v1/users/{user_id}', 'UserController@get_by_id');
    Route::post('/v1/users/{user_id}/roles', 'UserController@add_roles');
    Route::patch('/v1/users/profile/{user_id}', 'UserController@profile_update');


    /*
     * ROLE ENDPOINT
     * */
    Route::get('/v1/roles', 'RoleController@get_all');
    Route::post('/v1/roles', 'RoleController@create');
    Route::get('/v1/roles/{rol_id}', 'RoleController@get_by_id');
    Route::get('/v1/roles/by_company/{rol_id}', 'RoleController@get_by_company_id');
    Route::patch('/v1/roles/{role_id}', 'RoleController@update');
    Route::delete('/v1/roles/{role_id}', 'RoleController@delete');

    /*
     * Permissions ENDPOINT
     * */
    Route::get('/v1/permissions', 'PermissionController@get_all');
    Route::post('/v1/permissions', 'PermissionController@create');
    Route::get('/v1/permissions/{rol_id}', 'PermissionController@get_by_id');
    Route::get('/v1/permissions/by_company/{rol_id}', 'PermissionController@get_by_company_id');
    Route::put('/v1/permissions/{role_id}', 'PermissionController@update');

      /*
     * Notifications ENDPOINT
     * */
    Route::get('/v1/notifications', 'NotificationController@get_all');
   

    /*
     * Permissions ENDPOINT
     * */
    Route::get('/v1/role_permissions', 'RolePermissionController@get_all');
    Route::post('/v1/role_permissions', 'RolePermissionController@create');
    Route::get('/v1/role_permissions/{params}', 'RolePermissionController@get_by_params');
    Route::put('/v1/role_permissions/{role_id}', 'RolePermissionController@update');


    /*
     * COMPANY ENDPOINT
     * */
    Route::get('/v1/companies/e360_super_user', 'CompanyController@get_active');
    Route::get('/v1/companies/all', 'CompanyController@get_all');
    Route::get('/v1/companies/first_company_user/{user_id}', 'CompanyController@get_for_prime_user');
    Route::get('/v1/companies/company_user/{company_id}', 'CompanyController@get_by_id');
    Route::post('/v1/companies', 'CompanyController@create');
   // Route::get('/v1/companies/{company_id}', 'CompanyController@get_by_id');
    Route::delete('/v1/companies/{company_id}', 'CompanyController@delete');
    Route::patch('/v1/companies/{company_id}', 'CompanyController@update');

    /*
     * STATION USER ENDPOINT
     * */
    Route::get('/v1/company_users', 'CompanyUserController@get_all');
    Route::post('/v1/company_users', 'CompanyUserController@create');
    Route::get('/v1/company_users/{user_id}', 'CompanyUserController@get_by_id');
    Route::get('/v1/company_users/by_company/{company_id}', 'CompanyUserController@get_by_company_id');
    Route::post('/v1/company_users/{user_id}/roles', 'CompanyUserController@add_roles');
    Route::patch('/v1/company_users/{user_id}', 'CompanyUserController@update');
    Route::patch('/v1/company_users/profile/{user_id}', 'CompanyUserController@profile_update');
    Route::delete('/v1/company_users/{user_id}', 'CompanyUserController@delete');


    /*
     * NOTIFICATONS  SETTINGS ENDPOINT
     * */
    Route::get('/v1/notification_settings', 'CompanyNotificationsController@get_by_params');
    Route::post('/v1/notification_settings', 'CompanyNotificationsController@create');
    
    /*
     *Company Notifications ENDPOINT
     * */
    Route::get('/v1/company_notifications', 'CompanyNotificationsController@get_by_params');  
    Route::post('/v1/company_notifications', 'CompanyNotificationsController@create');
    Route::get('/v1/company_notifications/{id}', 'CompanyNotificationsController@get_by_id');
 

     /*
     *Company Permissions ENDPOINT
     * */
    Route::get('/v1/company_permissions', 'CompanyPermissionController@get_by_params');
    Route::post('/v1/company_permissions', 'CompanyPermissionController@create');
    Route::get('/v1/company_permissions/{id}', 'CompanyPermissionController@get_by_id');
    Route::put('/v1/company_permissions/{id}', 'CompanyPermissionController@update');


      /*
     *Company Permissions ENDPOINT
     * */
    Route::get('/v1/company_bank_accounts', 'CompanyBankAccountController@get_by_params');
    Route::post('/v1/company_bank_accounts', 'CompanyBankAccountController@create');
    Route::get('/v1/company_bank_accounts/{id}', 'CompanyBankAccountController@get_by_id');
    Route::put('/v1/company_bank_accounts/{id}', 'CompanyBankAccountController@update');
    Route::delete('/v1/company_bank_accounts/{id}', 'CompanyBankAccountController@delete');


     /*
     * REGION ENDPOINT
     * */
    Route::get('/v1/regions', 'RegionController@get_all');
    Route::post('/v1/regions', 'RegionController@create');
    Route::get('/v1/regions/{rol_id}', 'RegionController@get_by_id');
    Route::get('/v1/regions/by_company/{rol_id}', 'RegionController@get_by_company_id');
    Route::patch('/v1/regions/{role_id}', 'RegionController@update');
    Route::delete('/v1/regions/{role_id}', 'RegionController@delete');


    /*
     * STATIONs ENDPOINT
     * */
    Route::get('/v1/stations', 'StationController@get_all');
    Route::post('/v1/stations', 'StationController@create');
    Route::get('/v1/stations/{station_id}', 'StationController@get_by_id');
    Route::patch('/v1/stations/{station_id}', 'StationController@update');
    Route::delete('/v1/stations/{station_id}', 'StationController@delete');
    //Route::get('/v1/stations', 'StationController@get_company_by_station');
    Route::get('/v1/stations/by_company/{company_id}', 'StationController@get_stations_by_company_id');
    Route::get('/v1/stations/by_state/{state}', 'StationController@get_station_by_state');
    Route::get('/v1/stations/by_user/{user_id}', 'StationController@get_stations_by_user_id');

 /*
     * STATIONs ENDPOINT
     * */
    Route::get('/v1/demo_stations', 'DemoStationController@get_all');
    //Route::get('/v1/stations', 'StationController@get_company_by_station');
    Route::get('/v1/demo_stations/by_company/{company_id}', 'DemoStationController@get_stations_by_company_id');
    Route::get('/v1/demo_stations/by_state/{state}', 'DemoStationController@get_station_by_state');

    /*
     * TANK GROUPS ENDPOINT
     * */
    Route::get('/v1/tank_groups', 'TankGroupsController@get_all');
    Route::post('/v1/tank_groups', 'TankGroupsController@create');
    Route::get('/v1/tank_groups/{tank_group_id}', 'TankGroupsController@get_by_id');
    Route::put('/v1/tank_groups/{tank_group_id}', 'TankGroupsController@update');
    Route::delete('/v1/tank_groups/{tank_group_id}', 'TankGroupsController@delete');

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
    Route::delete('/v1/tanks/{tank_id}', 'TanksController@delete');


    /*
     * PUMP GROUPS ENDPOINT
     * */
    Route::get('/v1/pump_groups', 'PumpGroupsController@get_all');
    Route::post('/v1/pump_groups', 'PumpGroupsController@create');
    Route::get('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@get_by_id');
    Route::put('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@update');
    Route::delete('/v1/pump_groups/{pump_group_id}', 'PumpGroupsController@delete');
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
    Route::post('/v1/product_price_change', 'ProductPriceChangeLogsController@create_default');
    Route::post('/v1/product_price_change/request/', 'ProductPriceChangeLogsController@create_new_request');
    Route::post('/v1/product_price_change/execute/', 'ProductPriceChangeLogsController@execute_approval');
    Route::get('/v1/product_price_change/{product_price_change_id}', 'ProductPriceChangeLogsController@get_by_id');
     Route::get('/v1/product_price_change/by_station/{product_price_change_id}', 'ProductPriceChangeLogsController@get_by_station_id');
    Route::put('/v1/product_price_change/{product_price_change_id}', 'ProductPriceChangeLogsController@update');
    Route::get('/v1/product_price_change/verify_approval/{params}', 'ProductPriceChangeLogsController@verify_approval');

    /*
     * PUMPS ENDPOINT
     * */
    Route::get('/v1/pumps', 'PumpController@get_all');
    Route::post('/v1/pumps', 'PumpController@create');
    Route::get('/v1/pumps/{pump_id}', 'PumpController@get_by_id');
    Route::get('/v1/pumps/by_station/{station_id}', 'PumpController@get_by_station_id');
    Route::patch('/v1/pumps/{pump_id}', 'PumpController@update');
    Route::delete('/v1/pumps/{pump_id}', 'PumpController@delete');


     /*
     * PUMPS to Tanks ENDPOINT
     * */
    Route::get('/v1/pumps-tanks', 'PumpGroupToTankGroupController@get_all');
    Route::post('/v1/pumps-tanks', 'PumpGroupToTankGroupController@create');
    Route::get('/v1/pumps-tanks/{pump_id}','PumpGroupToTankGroupController@get_by_id');
    Route::get('/v1/pumps-tanks/by_station/{station_id}', 'PumpGroupToTankGroupController@get_by_station_id');
    Route::patch('/v1/pumps-tanks/{pump_id}', 'PumpGroupToTankGroupController@update');
    Route::delete('/v1/pumps-tanks/{pump_tank_id}', 'PumpGroupToTankGroupController@delete');
  
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
    Route::post('/v1/stock-readings/parsed_csv_data', 'DailyStockReadingsController@parsed_csv_data');
    Route::post('/v1/stock-readings/bovas_parsed_csv_data', 'DailyStockReadingsController@bovas_parsed_csv_data');
    Route::post('/v1/stock-readings/file_upload', 'DailyStockReadingsController@file_upload');    
    Route::post('/v1/stock-readings/bovas_file_upload', 'DailyStockReadingsController@bovas_file_upload');
    Route::get('/v1/stock-readings/{params}', 'DailyStockReadingsController@get_by_params');
    Route::delete('/v1/stock-readings', 'DailyStockReadingsController@delete_by_params');
    Route::patch('/v1/stock-readings', 'DailyStockReadingsController@update');

     /*
     * TOTALIZERS READINGS ENDPOINT
     * */
    Route::get('/v1/pump-readings', 'DailyTotalizersReadingsController@get_all');
    Route::post('/v1/pump-readings', 'DailyTotalizersReadingsController@create');
    Route::post('/v1/pump-readings/parsed_csv_data', 'DailyTotalizersReadingsController@parsed_csv_data');
    Route::post('/v1/pump-readings/file_upload', 'DailyTotalizersReadingsController@file_upload');
    Route::post('/v1/pump-readings/bovas_file_upload', 'DailyTotalizersReadingsController@bovas_file_upload');

    Route::get('/v1/pump-readings/{params}', 'DailyTotalizersReadingsController@get_by_params');
    Route::delete('/v1/pump-readings', 'DailyTotalizersReadingsController@delete_by_params');
    Route::patch('/v1/pump-readings', 'DailyTotalizersReadingsController@update');


    /*
     * STORE ITEMS  ENDPOINT
     * */
    Route::get('/v1/items', 'ItemController@get_all');
    Route::post('/v1/items', 'ItemController@create');
    Route::get('/v1/items/{params}', 'ItemController@get_by_params');
    Route::get('/v1/items/by_company/{company_id}', 'ItemController@get_by_company_id');
    Route::patch('/v1/items', 'ItemController@update');
    Route::delete('/v1/items/{item_id}', 'ItemController@delete');


      /*
     * ITEM VARIANTS  ENDPOINT
     * */
    Route::get('/v1/item-variants', 'ItemVariantController@get_all');
    Route::post('/v1/item-variants', 'ItemVariantController@create');
    Route::post('/v1/item-variants/stock-refill/', 'ItemVariantController@stock_refill');
    Route::post('/v1/item-variants/stock-count/', 'ItemVariantController@stock_count');
    Route::post('/v1/item-variants/stock-sales/', 'ItemVariantController@stock_sales');
    Route::post('/v1/item-variants/stock-transfer/', 'ItemVariantController@post_stock_transfer');
    Route::patch('/v1/item-variants/stock-transfer/{param}', 'ItemVariantController@patch_stock_transfer');
    Route::get('/v1/item-variants/stock-transfer/{param}', 'ItemVariantController@get_stock_transfer');
    Route::get('/v1/item-variants/by_item/{item_id}', 'ItemVariantController@get_by_item_id');
    Route::get('/v1/item-variants/by_station/{params}', 'ItemVariantController@get_by_params');
    Route::get('/v1/item-variants/stock-sales/{params}', 'ItemVariantController@get_stock_sales');
    Route::get('/v1/item-variants/stock-count/{params}', 'ItemVariantController@get_stock_count');
    Route::get('/v1/item-variants/stock-refill/{params}', 'ItemVariantController@get_stock_fills');
    Route::patch('/v1/item-variants', 'ItemVariantController@update');
    Route::delete('/v1/item-variants/{item_id}', 'ItemVariantController@delete');
     /*
     * Fuel Supply ENDPOINT
     * */
    Route::get('/v1/fuel-supply', 'FuelSupplyController@get_all');
    Route::post('/v1/fuel-supply', 'FuelSupplyController@create');
    Route::get('/v1/fuel-supply/{params}', 'FuelSupplyController@get_by_params');
    Route::get('/v1/fuel-supply/by_request_code/{params}', 'FuelSupplyController@get_by_request_code');
    Route::patch('/v1/fuel-supply', 'FuelSupplyController@update');

     /*
     * Stock Received ENDPOINT
     * */
    Route::get('/v1/stock-received', 'StockReceivedController@get_all');
    Route::post('/v1/stock-received', 'StockReceivedController@create');
    Route::get('/v1/stock-received/{params}', 'StockReceivedController@get_by_params');
    Route::patch('/v1/stock-received', 'StockReceivedController@update');

      /*
     * Expenses ENDPOINT
     * */
    Route::get('/v1/expenses', 'ExpensesController@get_all');
    Route::post('/v1/expenses', 'ExpensesController@create');
    Route::get('/v1/expenses/by_station/{param}', 'ExpensesController@get_by_station_id');
    Route::patch('/v1/expenses', 'ExpensesController@update');

      /*
     * Expenses ENDPOINT
     * */
    Route::get('/v1/deposits', 'DepositsController@get_all');
    Route::post('/v1/deposits', 'DepositsController@create');
    Route::get('/v1/deposits/by_station/{param}', 'DepositsController@get_by_station_id');
    Route::get('/v1/deposits/validate_amount/{param}', 'DepositsController@validate_amount');
    Route::patch('/v1/deposits', 'DepositsController@update');
    Route::post('/v1/deposits/parsed_csv_data', 'DepositsController@parsed_csv_data');
    Route::post('/v1/deposits/file_upload', 'DepositsController@file_upload');
    

  /*
     * ROPS ENDPOINT
     * */
    Route::get('/v1/rops', 'ROPSController@get_by_params');
    Route::post('/v1/rops', 'ROPSController@create');
    Route::patch('/v1/rops', 'ROPSController@update');


    /*
     * ROPS ENDPOINT
     * */
    Route::get('/v1/cops', 'COPSController@get_by_params');
    Route::post('/v1/cops', 'COPSController@create');
    Route::patch('/v1/cops', 'COPSController@update');

    /*
     * ROLE ENDPOINT
     * */
    Route::get('/v1/cops_lcd_config', 'COPSlcdconfigController@get_all');
    Route::post('/v1/cops_lcd_config', 'COPSlcdconfigController@create');
    Route::get('/v1/cops_lcd_config/{rol_id}', 'COPSlcdconfigController@get_by_id');
    Route::get('/v1/cops_lcd_config/by_company/{company}', 'COPSlcdconfigController@get_by_company_id');
    Route::patch('/v1/cops_lcd_config/{config_id}', 'COPSlcdconfigController@update');
    Route::delete('/v1/cops_lcd_config/{config_id}', 'COPSlcdconfigController@delete');

/*
     *Velox Customers ENDPOINT
     * */
    Route::get('/v1/velox_customer_accounts', 'VeloxCustomerController@get_by_params');
    Route::post('/v1/velox_customer_accounts', 'VeloxCustomerController@create');
    Route::get('/v1/velox_customer_accounts/{id}', 'VeloxCustomerController@get_by_id');
    Route::patch('/v1/velox_customer_accounts/{id}', 'VeloxCustomerController@update');

/*
     *Velox Payment ENDPOINT
     * */
    Route::get('/v1/velox_manage_payments', 'VeloxPaymentController@get_by_params');
    Route::post('/v1/velox_manage_payments', 'VeloxPaymentController@create');
    Route::get('/v1/velox_manage_payments/{id}', 'VeloxPaymentController@get_by_id');
    Route::patch('/v1/velox_manage_payments/{id}', 'VeloxPaymentController@update');

/*
     *Facility Maintenance ENDPOINT
     * */
    Route::get('/v1/equipment_maintenance/pump_readings', 'EquipmentMaintenanceController@get_pump_readings');
    
    Route::get('/v1/equipment_maintenance/get_pump_maintenance_and_current_readings', 'EquipmentMaintenanceController@get_pump_maintenance_and_current_readings');

    Route::post('/v1/equipment_maintenance/create_pump_maintenance_log', 'EquipmentMaintenanceController@create_pump_maintenance_log');
     Route::get('/v1/equipment_maintenance/get_pump_maintenance_log', 'EquipmentMaintenanceController@get_pump_maintenance_log');
   // Route::get('/v1/equipment_maintenance/{id}', 'EquipmentMaintenanceController@get_by_id');
   // Route::patch('/v1/equipment_maintenance/{id}', 'EquipmentMaintenanceController@update');

    /*
     *Velox Payment ENDPOINT
     * */
    Route::get('/v1/velox_manage_creditlimits', 'VeloxCreditLimitController@get_by_params');
    Route::post('/v1/velox_manage_creditlimits', 'VeloxCreditLimitController@create');
    Route::get('/v1/velox_manage_creditlimits/{id}', 'VeloxCreditLimitController@get_by_id');
    Route::patch('/v1/velox_manage_creditlimits/{id}', 'VeloxCreditLimitController@update');

/*
     *Velox Purchases ENDPOINT
     * */
    Route::get('/v1/velox_manage_purchases', 'VeloxPurchaseController@get_by_params');
    Route::get('/v1/velox_manage_purchases/{id}', 'VeloxPurchaseController@get_by_id');
    
     /*
       Dashboard ENDPOINT
        */
    Route::get('/v1/dashboard', 'DashboardController@get_filtered');

    Route::get('/v1/test/token', 'CompanyController@get_token');

});