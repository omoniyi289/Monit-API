<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAndRemoveColumnsFromPumpsAndTanks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tanks', function($table) {
             $table->dropColumn('shape');
             $table->dropColumn('name');
             $table->dropColumn('low_volume');
             $table->dropColumn('max_temperate');
             $table->dropColumn('daily_budget');
             
          });
        Schema::table('pumps', function($table) {
             $table->dropColumn('nozzle_code');
             $table->dropColumn('number');
             $table->dropColumn('type');
          });
        Schema::table('tanks', function (Blueprint $table) {
            $table->string('probe_id')->nullable();
            $table->string('atg_tank_id')->nullable();
            $table->string('type')->nullable();
           
        });  

        Schema::table('pumps', function (Blueprint $table) {
            $table->string('fcc_pump_nozzle_id')->after('product_id')->nullable();
            $table->string('pump_nozzle_code')->after('id');
            
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
