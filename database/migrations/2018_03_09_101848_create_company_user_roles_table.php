<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateCompanyUserRolesTable extends Migration
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
            Schema::create('company_user_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('company_user_id');
                $table->unsignedInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
                $table->foreign('company_user_id')->references('id')->on('company_users')->onUpdate('cascade');
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
            Schema::dropIfExists('company_user_roles');
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }
}
