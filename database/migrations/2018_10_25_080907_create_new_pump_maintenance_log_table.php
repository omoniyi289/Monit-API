<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewPumpMaintenanceLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_pump_maintenance_log', function (Blueprint $table) {
            $table->increments('id');

            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            $table->string('combined_pump_nozzle_code')->nullable();
            $table->string("pump_1_nozzle_code")->nullable();
            $table->string("pump_2_nozzle_code")->nullable();

            $table->string("totalizer_1_reading")->nullable();
            $table->string("totalizer_2_reading")->nullable();

            $table->string("combined_totalizer_reading")->nullable();
            $table->string("D_issue_date")->nullable();
            $table->string("MD_issue_date")->nullable();
            $table->string("MMD_issue_date")->nullable();
            $table->string("D_invoice_number")->nullable();
            $table->string("MD_invoice_number")->nullable();
            $table->string("MMD_invoice_number")->nullable();
            $table->string("D_maintenenance_date")->nullable(); //500K
            $table->string("MD_maintenenance_date")->nullable(); //1.5M
            $table->string("MMD_maintenenance_date")->nullable(); //2.5M
            $table->string("note")->nullable();
            $table->string("product")->nullable();
            $table->unsignedInteger("created_by")->nullable();
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
        Schema::dropIfExists('new_pump_maintenance_log');
    }
}
