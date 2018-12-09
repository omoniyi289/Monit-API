<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaybillcreatedbyAndstockreceivedbyToStockReceivedTable extends Migration
{
   
 
    public function up()
    {
        Schema::table('stock_received', function($table) {
             $table->dropColumn('created_by');
             
          });
   
        Schema::table('stock_received', function (Blueprint $table) {
            $table->string('waybill_printed_by')->nullable();
            $table->string('stock_received_by')->nullable();
           
        });  
    }

    public function down()
    {
        //
    }
}
