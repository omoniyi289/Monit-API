<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockSealNumbersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_seal_numbers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('stock_received_id')->nullable();
            $table->string('request_code')->nullable();
            $table->string('compartment_number')->nullable();
            $table->string('previous_seal_number')->nullable();
            $table->string('latest_seal_number')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_seal_numbers');
    }
}
