<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePumpMaintenanceLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_maintenance_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('pump_id')->nullable();
            $table->string("nozzle_code")->nullable();
            $table->integer("totalizer_before_maintenance")->nullable();
            $table->integer("totalizer_after_maintenance")->nullable();
            $table->string("maintenance_date")->nullable();
            $table->string("note")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_maintenance_log');
    }
}
