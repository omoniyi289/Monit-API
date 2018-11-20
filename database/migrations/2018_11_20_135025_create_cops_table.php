<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cops', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('company_id');
            $table->integer('station_id');
            $table->integer('uploaded_by');
            
            $table->string('survey_date')->nullable();
            
            $table->string('location')->nullable();
            $table->string('competitor')->nullable();
            $table->string('d2d')->nullable();

            $table->string('omp_pms')->nullable();
            $table->string('company_pms')->nullable();
            
            $table->string('omp_ago')->nullable();
            $table->string('company_ago')->nullable();
            
            $table->string('omp_dpk')->nullable();
            $table->string('company_dpk')->nullable();
            
            $table->string('omp_lube')->nullable();
            $table->string('company_lube')->nullable();
            
            $table->string('omp_lpg')->nullable();
            $table->string('company_lpg')->nullable();
            $table->string('note')->nullable();

            

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
        Schema::dropIfExists('cops');
    }
}
