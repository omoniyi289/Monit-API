<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemRestockHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('item_restock_history', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');    
            //$table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
            $table->integer('company_id')->nullable();    
            $table->integer('station_id')->nullable();
            $table->string('restock_id');
            $table->string('compositesku');
            $table->integer('restock_qty');
            $table->string('qty_before_restock')->nullable();    
            $table->string('qty_after_restock')->nullable();; 
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();             
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('item_restock_history');
    }
}
