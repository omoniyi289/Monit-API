<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopsLcdConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cops_lcd_config', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');   
            $table->string('name')->nullable();   
            $table->string('type')->nullable();   
            $table->string('status')->nullable();   
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
        Schema::dropIfExists('cops_lcd_config');
    }
}
