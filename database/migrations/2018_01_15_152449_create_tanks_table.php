<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tanks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name')->nullable();;
            //$table->string('width')->nullable();
            //$table->string('height')->nullable();
            $table->string('shape')->nullable();
            $table->string('capacity')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('low_volume')->nullable();
            $table->string('reorder_volume');
            $table->string('deadstock')->nullable();
            $table->string('max_temperate')->nullable();
            $table->string('max_water_level')->nullable();
            $table->integer('daily_budget');
            $table->integer('tank_group_id')->nullable();
            $table->integer('company_id');
            $table->integer('station_id');
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
        Schema::dropIfExists('tanks');
    }
}
