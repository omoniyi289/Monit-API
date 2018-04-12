<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateUserRolesTable extends Migration
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
            Schema::create('user_roles', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade');
                $table->timestamps();$table->softDeletes();
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
            Schema::dropIfExists('user_roles');
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }
}
