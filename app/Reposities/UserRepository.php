<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/10/18
 * Time: 11:15 AM
 */

namespace App\Reposities;

use App\User;
use Core\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Exception;


class UserRepository extends BaseRepository
{
    function get_model()
    {
        return new User();
    }

    public function create(array $data){
        $user = $this->get_model();
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function update(User $user, array $data){
        $user->fill($data);
        $user->save();
        return $user;
    }

    public function set_role(User $user, array $add_roles, array $remove_roles = []) {
        $this->database->beginTransaction();
        try{
            if (count($remove_roles) > 0){
                $query = $this->database->table($user->roles()->getTable());
                $query->where('user_id',$user->id)
                    ->whereIn('role',$remove_roles)
                    ->delete();
            }
            if (count($add_roles) > 0){
                $query = $this->database->table($user->roles()->getTable());
                $query->insert(array_map(function ($role_id) use ($user) {
                    return [
                        'role_id' => $role_id,
                        'user_id' => $user->id
                    ];
                },$add_roles));
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
    }

    public function filterIsAdministrator(Builder $query,$method,$clause_operator,$value,$in){
        if ($value){
            $query->whereIn('roles.name',['Adminstrator']);
        }
    }
}