<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemVariantRequest;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\MigrationService;

class MigrationController extends BaseController
{
    private $migration_service;

    public function __construct(MigrationService $migration_service)
    {
        $this->migration_service = $migration_service;
    }
 
    public function user_migrate(Request $request) {
        $data = $this->migration_service->user_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }

     public function company_migrate(Request $request) {
        $data = $this->migration_service->company_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function station_migrate(Request $request) {
        $data = $this->migration_service->station_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function role_migrate(Request $request) {
        $data = $this->migration_service->role_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function user_role_migrate(Request $request) {
        $data = $this->migration_service->user_role_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
      public function role_perm_migrate(Request $request) {
        $data = $this->migration_service->role_perm_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }

    public function user_station_migrate(Request $request) {
        $data = $this->migration_service->user_station_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function user_notf_migrate(Request $request) {
        $data = $this->migration_service->user_notf_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }

    public function pump_migrate(Request $request) {
        $data = $this->migration_service->pump_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function tank_migrate(Request $request) {
        $data = $this->migration_service->tank_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function tankgroup_migrate(Request $request) {
        $data = $this->migration_service->tankgroup_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function pumpgroup_migrate(Request $request) {
        $data = $this->migration_service->pumpgroup_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function p_t_map_migrate(Request $request) {
        $data = $this->migration_service->p_t_map_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function dsr_migrate(Request $request) {
        $data = $this->migration_service->dsr_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function dtr_migrate(Request $request) {
        $data = $this->migration_service->dtr_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function preadings_migrate(Request $request) {
        $data = $this->migration_service->preadings_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }

     public function treadings_migrate(Request $request) {
        $data = $this->migration_service->treadings_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function preadings_update_migrate(Request $request) {
        $data = $this->migration_service->preadings_update_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }

     public function treadings_update_migrate(Request $request) {
        $data = $this->migration_service->treadings_update_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function pt1_product_migrate(Request $request) {
        $data = $this->migration_service->pt1_product_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function pt2_product_migrate(Request $request) {
        $data = $this->migration_service->pt2_product_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function pp_migrate(Request $request) {
        $data = $this->migration_service->pp_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function pplog_migrate(Request $request) {
        $data = $this->migration_service->pplog_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function deposits_migrate(Request $request) {
        $data = $this->migration_service->deposits_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function expense_header_migrate(Request $request) {
        $data = $this->migration_service->expense_header_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
     public function expense_items_migrate(Request $request) {
        $data = $this->migration_service->expense_items_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function items_migrate(Request $request) {
        $data = $this->migration_service->items_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function item_variants_migrate(Request $request) {
        $data = $this->migration_service->item_variants_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function stock_transfer_migrate(Request $request) {
        $data = $this->migration_service->stock_transfer_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function stock_count_migrate(Request $request) {
        $data = $this->migration_service->stock_count_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
}