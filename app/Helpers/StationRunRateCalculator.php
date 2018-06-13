<?php
namespace App\Helpers;

	

class StationRunrateHelper{

	private $database;
    private $query='';
    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    private function computeRunRate($model){

    }

}