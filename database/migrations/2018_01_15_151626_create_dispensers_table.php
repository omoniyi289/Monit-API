<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispensersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispensers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nozzle_number');
            $table->string('oem_nozzle_id');
            $table->integer('pump_id');
            $table->integer('station_id');
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
        Schema::dropIfExists('dispensers');
    }
}
