<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBrowsersDetailsToLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_login_activity_log', function (Blueprint $table) {
            $table->string('browser_name')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os_version')->nullable();
            $table->string('location_cordinate')->nullable();
            
           
           
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
