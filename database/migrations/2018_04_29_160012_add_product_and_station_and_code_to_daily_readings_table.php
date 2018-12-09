<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductAndStationAndCodeToDailyReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('daily_stock_readings', function (Blueprint $table) {
            $table->string('product')->nullable();   
        });
         Schema::table('daily_totalizer_readings', function (Blueprint $table) {
            $table->string('product')->nullable(); 
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
