<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsToDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('cash_bank_deposits', function (Blueprint $table) {
            $table->string('v1_id')->nullable(); 
        });   
         Schema::table('cash_bank_deposits', function($table) {
             $table->dropColumn('date');
          });
        
    }  


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
