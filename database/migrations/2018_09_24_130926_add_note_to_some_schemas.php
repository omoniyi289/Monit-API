<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteToSomeSchemas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('cash_bank_deposits', function (Blueprint $table) {
            $table->string('note')->nullable();              
        }); 
          Schema::table('expense_items', function (Blueprint $table) {
            $table->string('note')->nullable();              
        }); 
         Schema::table('daily_stock_readings', function (Blueprint $table) {
            $table->string('note')->nullable();              
        });
         Schema::table('daily_totalizer_readings', function (Blueprint $table) {
            $table->string('note')->nullable();              
        }); 
         Schema::table('fuel_supplies', function (Blueprint $table) {
            $table->string('note')->nullable();              
        });  
       
         Schema::table('rops', function (Blueprint $table) {
            $table->string('note')->nullable();              
        });  

         Schema::table('stock_received', function (Blueprint $table) {
            $table->string('note')->nullable();              
        });  

         Schema::table('stock_counts', function (Blueprint $table) {
            $table->string('note')->nullable();              
        });  

         Schema::table('stock_sales', function (Blueprint $table) {
            $table->string('note')->nullable();              
        });  

          Schema::table('stock_transfers', function (Blueprint $table) {
            $table->string('note')->nullable();              
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
