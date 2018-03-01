<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePumpGroupToTankGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pump_group_to_tank_group', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('tank_group_id');
            $table->unsignedInteger('pump_group_id');
            $table->string("name");
            $table->integer("company_id");
            $table->integer("active")->default(0);;
            $table->integer("station_id");
            $table->foreign('tank_group_id')->references('id')->on('tank_groups')->onUpdate('cascade');
            $table->foreign('pump_group_id')->references('id')->on('pump_groups')->onUpdate('cascade');
            //$table->primary(['tank_group_id', 'pump_group_id']);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pump_group_to_tank_group');
    }
}
