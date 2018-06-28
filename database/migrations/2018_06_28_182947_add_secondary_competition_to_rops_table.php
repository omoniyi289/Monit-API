<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSecondaryCompetitionToRopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('rops', function (Blueprint $table) {
            $table->string('sc1_name')->nullable();
            $table->string('sc2_name')->nullable();
            $table->string('sc3_name')->nullable();

            $table->string('sc1_price_pms')->nullable();
            $table->string('sc2_price_pms')->nullable();
            $table->string('sc3_price_pms')->nullable();

            $table->string('sc1_price_ago')->nullable();
            $table->string('sc2_price_ago')->nullable();
            $table->string('sc3_price_ago')->nullable();

            $table->string('sc1_price_dpk')->nullable();
            $table->string('sc2_price_dpk')->nullable();
            $table->string('sc3_price_dpk')->nullable();
             });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
