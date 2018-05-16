<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReadingDateToStockAndPump extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_stock_readings', function($table) {
             $table->string('reading_date')->nullable(); 
          });
        Schema::table('daily_totalizer_readings', function($table) {
             $table->string('reading_date')->nullable(); 
          });    }

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
