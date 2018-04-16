<?php
/**
 * Created by PhpStorm.
 * User: funmiayinde
 * Date: 1/14/18
 * Time: 2:17 AM
 */

namespace App\Services;

use Illuminate\Database\DatabaseManager;
use Illuminate\Events\Dispatcher;
use App\Models\NotificationModules;
class NotificationModulesService
{
    private $database;
    private $dispatcher;


    public function __construct(DatabaseManager $database,Dispatcher $dispatcher)
    {
        $this->database = $database;
        $this->dispatcher = $dispatcher;
    }

    public function create(array $data){
        $this->database->beginTransaction();
        try{
            $notf = NotificationModules::create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $notf;
    }

    public function get_permission_by_name($name){
        return NotificationModules::where('name',$name)->get();
    }

    public function get_all(array $options = []){
        return NotificationModules::all();
    }
    public function get_by_id($module_id, array $options = [])
    {
        return $this->get_requested_permission($module_id);
    }
    private function get_requested_permission($module_id, array $options = [])
    {
        return NotificationModules::where('id', $module_id)->get()->first();
    }
}