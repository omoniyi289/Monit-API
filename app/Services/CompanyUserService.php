<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:01 AM
 */

namespace App\Services;

use App\Reposities\PermissionRepository;
use App\Reposities\RoleRepository;
use App\Reposities\CompanyUserRepository;
use App\Reposities\UserRepository;
use App\Models\CompanyUserRole;
use App\Models\StationUsers;
use App\Models\CompanyUsers;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;


class CompanyUserService
{
    private $database;
    private $dispatcher;
    private $company_user_repository;
    private $role_repository;
    private $permission_repository;

    public function __construct(DatabaseManager $database, Dispatcher $dispatcher,
                                CompanyUserRepository $company_user_repository,
                                RoleRepository $role_repository,
                                PermissionRepository $permission_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->company_user_repository = $company_user_repository;
        $this->role_repository = $role_repository;
        $this->permission_repository = $permission_repository;
    }

    public function get_all($options = [])
    {
        return $this->company_user_repository->get($options);
    }

    public function create($data)
    {
        $this->database->beginTransaction();
        try {
            $company_user = $this->company_user_repository->create($data);
            ///add station_company_user
            foreach($data['selected_stations'] as $value) {
                    StationUsers::create(['company_user_id' => $company_user['id'], 'station_id' => $value['id']]);
                }
            

        } catch (Exception $exception) {
            // this means don't insert
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return CompanyUsers::where('id',$company_user['id'])->with('station_users.station')->with('role')->get()->first();
    }

    public function update($company_user_id, array $data)
    {
        $company_user = $this->get_requested_user($company_user_id);
        $this->database->beginTransaction();
        try {
            StationUsers::where('company_user_id',$company_user_id)->delete();
            $this->company_user_repository->update($company_user, $data);
            if(isset($data['selected_stations'])){
            foreach($data['selected_stations'] as $value) {
                    StationUsers::create(['company_user_id' => $company_user_id, 'station_id' => $value['id']]);
                }
            }
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return CompanyUsers::where('company_id',$data['company_id'])->with('station_users.station')->with('role')->get();
    }
      public function profile_update($company_user_id, array $data)
    {
        $company_user = $this->get_requested_user($company_user_id);
        $this->database->beginTransaction();
        try {
            $this->company_user_repository->update($company_user, $data);
          
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $company_user;
    }

    public function get_user_by_email($email)
    {
        return $this->company_user_repository->get_where("email", $email);
    }

    public function get_user_by_username($username)
    {
        return $this->company_user_repository->get_where("username", $username);
    }

    private function get_requested_user($user_id, array $options = [])
    {
        return $this->company_user_repository->get_by_id($user_id, $options);
    }

    public function get_by_id($user_id, array $options = [])
    {
        //return $this->get_requested_user($user_id);
        return CompanyUsers::where('id',$user_id)->with('station_users.station')->with('role')->get()->first();
    }
    public function delete($user_id, array $options = [])
    {   
        StationUsers::where('company_user_id',$user_id)->delete();
        CompanyUserRole::where('company_user_id',$user_id)->delete();
        return  CompanyUsers::where('id',$user_id)->delete();
    }
      public function get_by_company_id($company_id)
    {
       return CompanyUsers::where('company_id',$company_id)->with('station_users.station')->with('role')->get();
    }

    public function add_roles($user_id, array $role_ids)
    {
        $user = $this->get_requested_user($user_id, [
            'includes' => ['roles']
        ]);
        $current_roles = $user->roles->pluck('id')->toArray();
        // check if the role id exist
        $roles = $this->check_validity_of_roles($role_ids);
        // set the role to the current user
        $this->company_user_repository->set_role($user, $role_ids);
        // iterate through the roles id and add
        // the role to the users, to use in the response
        $roles->filter(function ($role) use ($current_roles) {
            return !in_array($role->id, $current_roles);
        })->each(function ($role) use ($user) {
            $user->roles->add($role);
        });
        return $user;
    }

    private function check_validity_of_roles(array $roles_ids = [])
    {
        $roles = $this->role_repository->get_where_in('id', $roles_ids);
        if (count($roles_ids) !== $roles->count()) {
            $missing = array_diff($roles_ids, $roles->pluck('id')->toArray());
//            throw new
        }
        return $roles;
    }

}