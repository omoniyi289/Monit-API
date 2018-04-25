<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreFieldsForRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('roles', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
           Schema::table('role_permissions', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });     }

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
