<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreatePumpsTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     * @throws Exception
     */
    public function up()
    {
        DB::beginTransaction();
        try {
            Schema::create('pumps', function (Blueprint $table) {
                $table->increments('id');
                $table->string("number")->nullable();
                $table->string("brand")->nullable();
                $table->string("serial_number")->nullable();
                $table->string("type")->nullable();
                $table->integer("dispenser_id");
                $table->integer("company_id");
                $table->integer("station_id");
                $table->integer("pump_group_id")->nullable();
                $table->string("nozzle_code")->nullable();
                $table->integer("product_id");
                $table->timestamps();
            });
        }catch (Exception $e){
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pumps');
    }
}
