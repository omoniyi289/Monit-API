<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id')->nullable();    
            $table->integer('station_id')->nullable();
            $table->string('name');
            $table->string('category')->nullable();;
            $table->string('brand')->nullable();;
            $table->string('uom')->nullable();;
            $table->string('description')->nullable();
            $table->string('supplier')->nullable();          
            $table->string('created_by')->nullable();
            $table->string('modified_by')->nullable();            
            $table->string('parentsku')->nullable();
            $table->integer('hasvariants')->default(1);
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
        Schema::dropIfExists('items');
    }
}
