<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreatePumpGroupsTable extends Migration
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
            Schema::create('pump_groups', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code');
                $table->string('name');
                $table->integer('station_id');
                $table->integer('company_id');
                $table->timestamps();
            });
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
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
        Schema::dropIfExists('pump_groups');
    }
}
