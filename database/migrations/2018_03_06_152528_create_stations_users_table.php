<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStationsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations_users', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_user_id');
            $table->unsignedInteger('station_id');
            $table->foreign('company_user_id')->references('id')->on('company_users')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade');
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
        Schema::dropIfExists('stations_users');
    }
}
