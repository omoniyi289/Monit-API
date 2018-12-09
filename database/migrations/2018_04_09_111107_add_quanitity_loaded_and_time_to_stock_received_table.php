<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuanitityLoadedAndTimeToStockReceivedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::table('stock_received', function (Blueprint $table) {
            $table->string('quantity_loaded')->after('quantity_requested')->nullable();
            $table->string('truck_departure_time')->after('driver_name')->nullable();
            
            
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
