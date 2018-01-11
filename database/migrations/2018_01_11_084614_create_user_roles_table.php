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
                $table->integer('role_id');
                $table->integer('user_id');
                $table->primary(['user_id', 'role_id']);
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
