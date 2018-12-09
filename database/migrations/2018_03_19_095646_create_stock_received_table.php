<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockReceivedTable extends Migration
{
   
    public function up()
    {
        Schema::create('stock_received', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('quantity_supplied')->nullable();
            $table->integer('quantity_requested');
            $table->integer('created_by');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            
            $table->string('truck_reg_number')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('arrival_time')->nullable();
            $table->string('quantity_before_discharge')->nullable();
            $table->string('quantity_after_discharge')->nullable();
            
            $table->string('waybill_number')->nullable();
            $table->string('request_code');
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade');
            
            $table->timestamps();$table->softDeletes();
        });
    }

    
    public function down()
    {
        Schema::dropIfExists('stock_recieved');
    }
}
