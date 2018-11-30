<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentStatusToMaintenanceLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('new_pump_maintenance_log', function (Blueprint $table) {
            $table->string('D_payment_status')->nullable();   
            $table->string('MMD_payment_status')->nullable();   
            $table->string('MD_payment_status')->nullable();   
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
