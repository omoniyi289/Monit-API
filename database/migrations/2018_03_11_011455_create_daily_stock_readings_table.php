<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyStockReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_stock_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('tank_id');
            $table->string("tank_code")->nullable();;
            $table->integer("phy_shift_start_volume_reading")->nullable();
            $table->integer("phy_shift_end_volume_reading")->nullable();
            $table->integer("atg_shift_start_volume_reading")->nullable();
            $table->integer("atg_shift_end_volume_reading")->nullable();
            $table->integer("start_delivery")->nullable();
            $table->integer("end_delivery")->nullable();
            $table->integer("return_to_tank")->nullable();
            //$table->integer("reading_date")->nullable();
            $table->unsignedInteger("created_by");
            $table->integer("last_modified_by")->nullable();
            $table->string("status")->nullable();
            $table->foreign('tank_id')->references('id')->on('tanks')->onUpdate('cascade');
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
        Schema::dropIfExists('daily_stock_readings');
    }
}
