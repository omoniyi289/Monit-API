<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdentifiersNeededForMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });    
        Schema::table('pumps', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });    
         Schema::table('tanks', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        }); 
        Schema::table('stations', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        });  
        Schema::table('users', function (Blueprint $table) {
            $table->string('v1_id')->nullable();
           
        }); 
        
        Schema::table('items', function (Blueprint $table) {
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
