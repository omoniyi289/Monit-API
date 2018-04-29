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
     public function pt_product_migrate(Request $request) {
        $data = $this->migration_service->pt_product_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
}