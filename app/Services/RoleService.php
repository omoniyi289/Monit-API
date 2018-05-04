<?php
namespace App\Services;

use App\Notifications\RolesAssigned;
use App\Reposities\RoleRepository;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use Exception;
use App\Role;
use App\RolePermission;
use App\Models\CompanyUserRole;
use App\Permission;

class RoleService
{
    private $database;
    private $dispatcher;
    private $role_repository;

    public function __construct(DatabaseManager $database,Dispatcher $dispatcher,
                                RoleRepository $role_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->role_repository = $role_repository;
    }

    public function create(array $data){
        try {
            $this->database->beginTransaction();
            try {
                $role = $this->role_repository->create($data);
                foreach ($data['selected_privileges'] as $value) {
                    $permission= Permission::where('id', $value)->get()->first();
                    RolePermission::create(['role_id' => $role['id'], 'permission_id' => $value['id'], 'company_id' => $data['company_id'], 'permission_name' => $permission['name']]);
                }
             
                

            } catch (Exception $exception) {
                $this->database->rollBack();
                throw $exception;
            }
            $this->database->commit();
             return Role::where("id",$role['id'])->with('role_permissions.permission')->get()->first();
        }catch (\Exception $exception){
            throw  $exception;
        }
    }

    public function get_role_by_name($name, $company_id){
      //  return $this->role_repository->get_where("name",$name);
        return Role::where("name",$name)->where("company_id",$company_id)->get();
    }
    public function get_id($role_id, array $options = []){
        return $this->get_requested_role($role_id);
    }

    private function get_requested_role($role_id, array $options = []){
        return $this->role_repository->get_by_id($role_id,$options);
    }
     public function get_role_permissions($role_id)
    {
        return Role::where("id",$role_id)->with('role_permissions.permission')->get()->first();     
    }
    public function update($role_id,array  $data){
        $role = $this->get_requested_role($role_id);
        $this->database->beginTransaction();
        try{
            $this->role_repository->update($role,$data);
            RolePermission::where('role_id', $role_id)->delete();
            foreach ($data['selected_privileges'] as $value) {
                    $permission= Permission::where('id', $value)->get()->first();
                    RolePermission::create(['role_id' => $role['id'], 'permission_id' => $value['id'], 'company_id' => $data['company_id'], 'permission_name' => $permission['name']]);
                }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return Role::where("company_id",$data['company_id'])->with('role_permissions.permission')->get();
        //return Role::where("id",$role['id'])->with('role_permissions')->get()->first();
    }
      public function get_by_company_id($company_id)
    {
       return Role::where("company_id",$company_id)->with('role_permissions.permission')->get();
    }
    public function delete($role_id, array $options = [])
    {   CompanyUserRole::where('role_id',$role_id)->delete();
        RolePermission::where('role_id', $role_id)->delete();
        return  Role::where('id',$role_id)->delete();
    }

    public function get_all($options = []){
        return $this->role_repository->get($options);
    }
}