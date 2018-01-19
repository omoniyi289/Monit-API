<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductChangeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_change_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('old_price_tag');
            $table->string('new_price_tag');
            $table->integer('company_id');
            $table->integer('product_id');
            $table->integer('updated_by');
            $table->integer('approved_by')->nullable();
            $table->boolean('is_approved');
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
        Schema::dropIfExists('product_change_logs');
    }
}
