<?php
/**
 * Created by PhpStorm.
 * User: funmi ayinde
 * Date: 1/10/18
 * Time: 11:01 AM
 */

namespace App\Services;

use App\Resposities\UserRepository;
use Exception;
use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;

class UserService
{
    private $database;
    private $dispatcher;
    private $user_repository;

    public function __construct(DatabaseManager $database, Dispatcher $dispatcher,
                                UserRepository $user_repository)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
        $this->user_repository = $user_repository;
    }

    public function get_all($options = [])
    {
        return $this->user_repository->get($options);
    }

    public function create($data)
    {
        $this->database->beginTransaction();
        try {
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
        return $this->user_repository->get_where("email", $email);
    }

    public function get_user_by_username($username)
    {
        return $this->user_repository->get_where("username", $username);
    }

    private function get_requested_user($user_id, array $options = [])
    {
        return $this->user_repository->get_by_id($user_id, $options);
    }

    public function get_by_id($user_id, array $options = []){
        return $this->get_requested_user($user_id);
    }

}