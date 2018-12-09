<?php
namespace App\Http\Controllers;

use App\Requests\ApiItemVariantRequest;
use Core\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Services\FGDemoMigrationService;

class FGDemoMigrationController extends BaseController
{
    private $migration_service;

    public function __construct(FGDemoMigrationService $migration_service)
    {
        $this->migration_service = $migration_service;
    }
 
     public function company_migrate(Request $request) {
        $data = $this->migration_service->company_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function station_migrate(Request $request) {
        $data = $this->migration_service->station_migrate();
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
     public function pt1_product_migrate(Request $request) {
        $data = $this->migration_service->pt1_product_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
    public function pt2_product_migrate(Request $request) {
        $data = $this->migration_service->pt2_product_migrate();
        return $this->response(1, 8000, "transfers", $data);
    }
   
}