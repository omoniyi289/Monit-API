<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValidFromAndExecutedBy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('product_change_logs', function (Blueprint $table) {
            $table->integer('executed_by')->nullable(); 
        });   
         Schema::table('product_change_logs', function($table) {
             $table->string('valid_from')->nullable(); 
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
