<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateStationsTable extends Migration
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
            Schema::create('stations', function (Blueprint $table) {
                $table->increments('id');
                $table->string("name");
                $table->string("address");
                $table->string("opening_time");
                $table->string("manager_name");
                $table->string("manager_phone");
                $table->string("manager_email");
                $table->string("city");
                $table->string("state");
                $table->string("daily_budget");
                $table->string("expenses_type")->nullable();
                $table->string("company_id");
                $table->integer("station_user_id");
                $table->integer("is_station_enabled")->default(0);;
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
        Schema::dropIfExists('stations');
    }
}
