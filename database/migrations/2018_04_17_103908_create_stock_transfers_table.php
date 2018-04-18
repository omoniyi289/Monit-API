<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->nullable();    
            $table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
            $table->integer('company_id')->nullable();    
            $table->integer('tx_station_id')->nullable();
            $table->integer('rx_station_id')->nullable();

            $table->integer('quantity_requested')->nullable();           
            $table->integer('quantity_transferred')->nullable();
            $table->integer('quantity_received')->nullable();

            $table->string('requested_by')->nullable();
            $table->string('transfered_by')->nullable();
            $table->string('approved_by')->nullable();
            $table->string('received_by')->nullable();
            
            $table->string('date_requested')->nullable();
            $table->string('date_transfered')->nullable();
            $table->string('date_received')->nullable();
            $table->string('date_approved')->nullable();

            $table->string('status')->nullable();    
            $table->integer('active')->default(1);
            $table->integer('in_stock')->default(1);
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
        Schema::dropIfExists('stock_transfers');
    }
}
