<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsForStations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('stations', function (Blueprint $table) {

            $table->integer('show_atg_dpk')->nullable();
            $table->integer('show_atg_ago')->nullable();
            $table->integer('show_atg_pms')->nullable();
            $table->integer('show_fcc_dpk')->nullable();
            $table->integer('show_fcc_ago')->nullable();
            $table->integer('show_fcc_pms')->nullable();
            $table->integer('show_atg_data')->nullable();
            $table->integer('show_fcc_data')->nullable();
            $table->integer('atg_active')->default(1);
            $table->integer('fcc_active')->default(1);

             $table->integer('hasFCC')->nullable();
             $table->integer('hasATG')->nullable();
             $table->integer('regionid')->nullable();
             $table->string('fcc_oem')->nullable();
             $table->string('atg_oem')->nullable();
        

            $table->integer('daily_pms_target')->nullable();
            $table->integer('daily_ago_target')->nullable();
            $table->integer('daily_dpk_target')->nullable();

            $table->integer('daystodelivery')->nullable();
            $table->integer('oem_stationid')->nullable();
     
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
