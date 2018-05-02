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
use App\Models\ExpenseHeader;
use App\Models\ExpenseItems;
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
            //$data['expense_code'] = date("Ymdmis");
            $data['expense_date'] = date_format(date_create($data['expense_date']),"Y-m-d")." 00:00:00";
            $items = $data['items'];
            //return $data;
            $expense = ExpenseHeader::create($data);
            foreach ($items as $key => $value) {
                $value['expense_id'] = $expense['id'];
                $item = ExpenseItems::create($value);
            }
        }catch (Exception $exception){
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return ExpenseHeader::with('station')->with('items')->where('id', $expense['id'])->get()->first();
    }
    
     public function update($expense_id, array $data)
    {
        $expense = ExpenseHeader::where('id',$expense_id);
        $this->database->beginTransaction();
        try {
            ExpenseHeader::where('id', $expense['id'])->update($data);
        } catch (Exception $exception) {
            $this->database->rollBack();
            throw $exception;
        }
        $this->database->commit();
        return $expense;
    }

    public function get_all(array $options = []){
        return ExpenseHeader::all();
    }
    public function get_by_id($id, array $options = [])
    {
        return get_requested_expense($id);
    }
      public function get_by_station_id($station_id)
    {
       return ExpenseHeader::with('station')->with('items')->where('station_id',$station_id)->get();
    }
    private function get_requested_expense($id, array $options = [])
    {
        return ExpenseHeader::where('id', $id)->get()->first();
    }
}