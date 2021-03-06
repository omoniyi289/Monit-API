<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:01 AM
 */

namespace App\Services;

use App\Reposities\RoleRepository;
use App\Reposities\UserRepository;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\User;

class UserService
{
    private $database;
    private $dispatcher;
    private $user_repository;
    private $role_repository;

    public function __construct(DatabaseManager $database, Dispatcher $dispatcher,
                                UserRepository $user_repository, RoleRepository $role_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->user_repository = $user_repository;
        $this->role_repository = $role_repository;
    }

    public function get_all($options = [])
    {
        return $this->user_repository->get($options);
    }
   

    public function create($data)
    {
        $this->database->beginTransaction();
        try {
            $data['role_id']= 'super';
            $data['company_id'] = 'super';
            $user = $this->user_repository->create($data);
        } catch (Exception $exception) {
            // this means don't insert
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $user;
    }

    public function update($user_id, array $data)
    {
        $user = $this->get_requested_user($user_id);
        $this->database->beginTransaction();
        try {
            if(isset($data['make_super_admin']) && $data['make_super_admin'] == 'Yes'){
                //make user an e360 super admin
                $data['company_id'] = 'master';
                $data['role_id'] = 'master';
            }
            $this->user_repository->update($user, $data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $user;
    }

    public function get_user_by_email($email)
    {
        //return $this->user_repository->get_where("email", $email);
        return User::where("email", $email)->where('status', 'Active')->get();
    }
    public function get_user_for_analytics($email)
    {
        $user = User::where("email", $email)->with('role.role_permissions.permission')->with('companies:id,name')->with('station_users.station.station_region.region:id,name')->get()->first();
        //$user = $user_repository->get_user_for_analytics($email);
        return $user;
        
    }
    ///e360 customer acquisation system
    public function get_user_for_ecas($email)
    {
        $user = User::where("email", $email)->with('role.role_permissions.permission')->with('companies:id,sms_sender_id')->with('station_users.station:id,name')->get()->first();
        return $user;
        
    }
    public function get_by_params($request)
    {
        $result = User::with('companies:id,name');
        if(isset($request['company_id'])){
            $company_id = $request['company_id'];
            $result = $result->where('company_id', $company_id);

        }  
        return $result->get();
    }

    public function get_user_by_username($username)
    {
        return $this->user_repository->get_where("username", $username);
    }

    private function get_requested_user($user_id, array $options = [])
    {
        return $this->user_repository->get_by_id($user_id, $options);
    }

    public function get_by_id($user_id, array $options = [])
    {
        return $this->get_requested_user($user_id);
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
        $this->user_repository->set_role($user, $role_ids);
        // iterate through the roles id and add the role to the users
        $roles->filter(function ($role) use ($current_roles) {
            return !in_array($role->id, $current_roles);
        })->each(function ($role) use ($user) {
            $user->roles->add($role);
        });
        $this->dispatcher->fire(new RolesAssigned($user));
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