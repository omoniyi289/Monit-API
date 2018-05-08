<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyTotalizerReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_totalizer_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('pump_id');
            $table->string("pump_number")->nullable();
            $table->string("nozzle_code")->nullable();
            $table->integer("open_shift_totalizer_reading")->nullable();
            $table->integer("close_shift_totalizer_reading")->nullable();

            $table->integer("shift_1_totalizer_reading")->nullable();
            $table->integer("shift_2_totalizer_reading")->nullable();
            $table->integer("shift_1_cash_collected")->nullable();
            $table->integer("shift_2_cash_collected")->nullable();
            $table->integer("cash_collected")->nullable();
            $table->integer("attendant")->nullable();
            $table->integer("ppv")->nullable();
            //$table->integer("reading_date")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->integer("last_modified_by")->nullable();
            $table->string("status")->nullable();
            $table->foreign('pump_id')->references('id')->on('pumps')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade');

            $table->timestamps();$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_totalizer_readings');
    }
}
