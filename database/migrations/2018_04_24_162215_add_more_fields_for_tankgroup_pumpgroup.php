<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsForTankgroupPumpgroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('tank_groups', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
           Schema::table('pump_groups', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        }); 

          Schema::table('expenses', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });

          Schema::table('stock_transfers', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });    
         Schema::table('stock_sales', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });    
          Schema::table('stock_counts', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });    
           Schema::table('daily_stock_readings', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });    
            Schema::table('daily_totalizer_readings', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
            Schema::table('itemvariants', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
            Schema::table('product_prices', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
            Schema::table('price_change_logs', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
            Schema::table('pump_group_to_tank_group', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
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
