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
                $table->string("opening_time")->nullable();;
                $table->string("manager_name")->nullable();;
                $table->string("manager_phone")->nullable();;
                $table->string("manager_email")->nullable();;
                $table->string("city");
                $table->string("state");
                $table->string("daily_budget")->nullable();;
                $table->string("monthly_budget")->nullable();;
                $table->string("license_type")->nullable();;
                $table->string("expenses_type")->nullable();
                $table->string("company_id");
                $table->integer("station_user_id")->nullable();;
                $table->integer("is_station_enabled")->default(1);
                $table->timestamps();
                $table->softDeletes();
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
