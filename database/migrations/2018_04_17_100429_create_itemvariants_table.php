<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemvariantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemvariants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');    
            //$table->foreign('item_id')->references('id')->on('items')->onUpdate('cascade');
            $table->integer('company_id')->nullable();    
            $table->string('variant_option')->nullable();;
            $table->string('variant_value')->nullable();;
            $table->integer('reorder_level')->nullable();;
            $table->integer('qty_in_stock')->nullable();;
            $table->string('last_restock_date')->nullable();    
            $table->string('supply_price')->nullable();;
            $table->string('retail_price')->nullable();          
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();            
            $table->string('sku')->nullable();    
            $table->integer('active')->default(1);
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
        Schema::dropIfExists('itemvariants');
    }
}
