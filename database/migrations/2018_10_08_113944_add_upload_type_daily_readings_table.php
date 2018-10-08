<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUploadTypeDailyReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('daily_stock_readings', function (Blueprint $table) {
            $table->string('upload_type')->nullable();              
        });

         Schema::table('daily_totalizer_readings', function (Blueprint $table) {
            $table->string('upload_type')->nullable();   
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
