<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       /*   Schema::table('expenses', function (Blueprint $table) {
            $table->string('expense_date')->nullable();    
             $table->string('expense_code')->nullable(); 
              $table->string('unit_amount')->nullable(); 
               $table->string('quantity')->nullable(); 
                $table->string('total_amount')->nullable(); 
        });*/
         Schema::table('cash_bank_deposits', function (Blueprint $table) {
            $table->string('reading_date')->nullable(); 
            $table->string('teller_date')->nullable();    
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
