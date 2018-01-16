<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCompaniesTable extends Migration
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
            Schema::create('companies', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('email')->unique();
                $table->string('registration_number', 6)->unique()->nullable();
                $table->string('country');
                $table->string('state');
                $table->string('city');
                $table->string('address');
                $table->string('logo')->nullable();
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
     * @return void
     * @throws Exception
     */
    public function down()
    {
        DB::beginTransaction();
        try {
            Schema::dropIfExists('companies');
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }
}
