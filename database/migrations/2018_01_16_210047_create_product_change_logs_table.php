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
            $table->string('requested_price_tag')->default(0);
            $table->string('current_price_tag');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('product_id');
            $table->unsignedInteger('updated_by')->nullable();
            $table->unsignedInteger('approved_by')->nullable();
            $table->boolean('is_approved')->nullable();
            $table->unsignedInteger('station_id');
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
        Schema::dropIfExists('product_change_logs');
    }
}
