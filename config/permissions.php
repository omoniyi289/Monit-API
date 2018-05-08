<?php
return [

        "admin" => [

                "create_user" => [
                    "name" => "Create/Modify Users",
                    "description" => "permission to Create Users for company",
                    "UI_slug" => "CMUs"
                ],

                "create_company" => [
                    "name" => "Create/Modify Company",
                    "description" => "permission to Modify Company Profile",
                    "UI_slug" => "CMCo"
                ],

                "setup_station" => [
                    "name" => "Create/Modify Stations",
                    "description" => "permission to setup stations",
                    "UI_slug" => "CMSt"
                ],

                "create_price_change_request" => [
                    "name" => "Create Price Change Request",
                    "description" => "permission to Create Price Change Request",
                    "UI_slug" => "CPCR"
                ],  

                "approve_price_change_request" => [
                    "name" => "Approve Price Change Request",
                    "description" => "permission to Create Price Change Request",
                    "UI_slug" => "APCR"
                ],

                 "execute_price_change_request" => [
                    "name" => "Execute Price Change Request",
                    "description" => "permission to Execute Price Change Request",
                    "UI_slug" => "EPCR"
                ],

                "create_roles" => [
                    "name" => "Create/Modify Roles",
                    "description" => "permission to create and modify roles",
                    "UI_slug" => "CMRo"
                ],
                            
                "analytics_previous" => [
                    "name" => "Analytics - Previous Day Stats",
                    "description" => "Analytics - Previous Day Stats",
                    "UI_slug" => "APDS"
                ],

                "analytics_expenses" => [
                    "name" => "Analytics - Expenses",
                    "description" => "Analytics - Expenses",
                    "UI_slug" => "AExp"
                ],
                "analytics_current" => [
                    "name" => "Analytics - Current Day Sales",
                    "description" => "Analytics - Current Day Sales",
                    "UI_slug" => "ACDS20"
                ],

                "analytics_historical" => [
                    "name" => "Analytics - Historical Data",
                    "description" => "Analytics - Historical Data",
                    "UI_slug" => "AHDa20"
                ],
                "analytics_stock_data" => [
                    "name" => "Analytics - Stock Data",
                    "description" => "Analytics - Stock Data",
                    "UI_slug" => "ASDa20"
                ],

                "analytics_recon" => [
                    "name" => "Analytics - Reconciliation",
                    "description" => "Analytics - Reconciliation",
                    "UI_slug" => "ARco20"
                ],
                "analytics_reports" => [
                    "name" => "Analytics - Reports",
                    "description" => "Analytics - Reports",
                    "UI_slug" => "ARep20"
                ],

                "analytics_cds" => [
                    "name" => "Analytics - Current Day Stock",
                    "description" => "Analytics - Current Day Stock",
                    "UI_slug" => "ACur20"
                ],

        ],


        "configuration" => [
                "setup_station_config" => [
                    "name" => "Setup Station Configuration",
                    "description" => "permission to Setup Station Configuration",
                    "UI_slug" => "SSCo"
                ],

                 "modify_station_config" => [
                    "name" => "Modify Station Configuration",
                    "description" => "permission to Modify Station Configuration",
                    "UI_slug" => "MSCo"
                ],
            
        ],

        "stock_management" => [
                "capture_sales" => [
                    "name" => "Capture Sales and Stock",
                    "description" => "permission to Capture Sales and Stock",
                    "UI_slug" => "CSSt"
                ],
                "modify_sales" => [
                    "name" => "Modify Sales and Stock",
                    "description" => "permission to Modify Sales and Stock",
                    "UI_slug" => "MSSt"
                ],             
        ],

        "cash_management" => [
                "capture_sales" => [
                    "name" => "Add and Manage Payments",
                    "description" => "permission to Add and Manage Payments",
                    "UI_slug" => "AMPa"
                ],   
        ],

        "expenses_management" => [
                "manage_expenses" => [
                    "name" => "Add and Manage Expenses",
                    "description" => "permission to Add and Manage Expenses",
                    "UI_slug" => "AMEx"
                ],             
        ],

        "fuel_supply" => [
                "request_supply" => [
                    "name" => "Request Fuel Supply",
                    "description" => "permission to Request Fuel Supply",
                    "UI_slug" => "RFSu"
                ],
                "approve_fuel_request" => [
                    "name" => "Approve/Update Fuel Request",
                    "description" => "permission to Approve/Update Fuel Request",
                    "UI_slug" => "AFRe"
                ],
                "process_fuel_request" => [
                    "name" => "Process Fuel Request",
                    "description" => "permission to Process Fuel Request",
                    "UI_slug" => "PFRe"
                ],
                "receive_stock" => [
                    "name" => "Receive Stock",
                    "description" => "permission to Receive Stock at Stations",
                    "UI_slug" => "RStk"
                ],
        ],

        "store_management" => [
                "manage_items" => [
                    "name" => "Store- Add/Modify Items",
                    "description" => "Add Items/Modify Items",
                    "UI_slug" => "AMIs20"
                ],
                "fill_items" => [
                    "name" => "Store- Fill/Refill Stock",
                    "description" => "permission to Fill/Refill Stock",
                    "UI_slug" => "FRSk20"
                ],
                 "count_items" => [
                    "name" => "Store- Count Stock",
                    "description" => "permission to count Stock",
                    "UI_slug" => "CStk20"
                ],
                "transfer_items" => [
                    "name" => "Store- Transfer Stock",
                    "description" => "permission to Transfer Stock",
                    "UI_slug" => "TStk20"
                ],
                "recieve_items" => [
                    "name" => "Store- Receive Stock",
                    "description" => "permission to Receive Stock at Stations",
                    "UI_slug" => "RStk20"
                ],
        ]
    ];
    