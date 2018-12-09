<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFuelSupplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fuel_supplies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity_requested');
            $table->integer('created_by');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('product_id');
            $table->integer('approved_by')->nullable();
            $table->integer('last_modified_by')->nullable();
            $table->integer('is_approved')->nullable();
            $table->unsignedInteger('station_id');
            $table->string('status');
            $table->string('request_code');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onUpdate('cascade');
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
        Schema::dropIfExists('fuel_supply');
    }
}
