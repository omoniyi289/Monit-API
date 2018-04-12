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
use App\Models\Expenses;
class ExpensesService
{
    private $database;
    private $pump_repository;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }
    public function create(array $data) {
        $this->database->beginTransaction();
        try{
            $data['expense_code'] = strtoupper(uniqid('EC'));
            $expenses = Expenses::create($data);
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $expenses;
    }
     public function update($expense_id, array $data)
    {
        $expense = Expenses::where('id',$expense_id);
        $this->database->beginTransaction();
        try {
            Expenses::where('id', $expense['id'])->update($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $expense;
    }

    public function get_all(array $options = []){
        return Expenses::all();
    }
    public function get_by_id($id, array $options = [])
    {
        return get_requested_expense($id);
    }
      public function get_by_station_id($station_id)
    {
       return Expenses::where('station_id',$station_id)->get();
    }
    private function get_requested_expense($id, array $options = [])
    {
        return Expenses::where('id', $id)->get()->first();
    }
}