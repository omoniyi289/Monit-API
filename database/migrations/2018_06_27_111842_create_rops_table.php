<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('station_id');
            $table->integer('uploaded_by');
            
            $table->string('survey_date')->nullable();
            $table->string('pc1_name')->nullable();
            $table->string('pc2_name')->nullable();
            $table->string('pc3_name')->nullable();

            $table->string('pc1_price_pms')->nullable();
            $table->string('pc2_price_pms')->nullable();
            $table->string('pc3_price_pms')->nullable();

            $table->string('pc1_price_ago')->nullable();
            $table->string('pc2_price_ago')->nullable();
            $table->string('pc3_price_ago')->nullable();

            $table->string('pc1_price_dpk')->nullable();
            $table->string('pc2_price_dpk')->nullable();
            $table->string('pc3_price_dpk')->nullable();

            $table->string('nearest_depot_name')->nullable();
            $table->string('nearest_depot_pms')->nullable();
            $table->string('nearest_depot_ago')->nullable();
            $table->string('nearest_depot_dpk')->nullable();

            $table->string('recommended_selling_price_pms')->nullable();
            $table->string('recommended_selling_price_ago')->nullable();
            $table->string('recommended_selling_price_dpk')->nullable();

            $table->string('current_selling_price_pms')->nullable();
            $table->string('current_selling_price_ago')->nullable();
            $table->string('current_selling_price_dpk')->nullable();
           
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
        Schema::dropIfExists('rops');
    }
}
