<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddV1IdToUserNotificationsAndStationUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::table('user_notifications', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
           Schema::table('stations_users', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
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
