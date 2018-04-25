<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReadindTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('daily_totalizer_readings', function (Blueprint $table) {
            $table->decimal('open_shift_totalizer_reading', 11, 2)->nullable()->change();
            $table->decimal('close_shift_totalizer_reading', 11, 2)->nullable()->change();
            $table->decimal('shift_1_totalizer_reading', 11, 2)->nullable()->change();
            $table->decimal('shift_2_totalizer_reading', 11, 2)->nullable()->change();
            $table->decimal('shift_1_cash_collected', 11, 2)->nullable()->change();
            $table->decimal('shift_2_cash_collected', 11, 2)->nullable()->change();
            $table->decimal('cash_collected', 11, 2)->nullable()->change();
            $table->decimal('ppv', 11, 2)->nullable()->change();

        });  

         Schema::table('daily_stock_readings', function (Blueprint $table) {
            $table->decimal('phy_shift_start_volume_reading', 11, 2)->nullable()->change();
            $table->decimal('phy_shift_end_volume_reading', 11, 2)->nullable()->change();
            $table->decimal('atg_shift_start_volume_reading', 11, 2)->nullable()->change();
            $table->decimal('atg_shift_end_volume_reading', 11, 2)->nullable()->change();
            $table->decimal('start_delivery', 11, 2)->nullable()->change();
            $table->decimal('end_delivery', 11, 2)->nullable()->change();
            $table->decimal('return_to_tank', 11, 2)->nullable()->change();

        });  
         Schema::table('daily_totalizer_readings', function (Blueprint $table) {
            $table->dropColumn('pump_number');
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
