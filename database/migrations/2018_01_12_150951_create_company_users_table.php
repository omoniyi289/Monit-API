<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
class CreateCompanyUsersTable extends Migration
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
            Schema::create('company_users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('fullname');
                $table->string('email')->unique();
                $table->string('username')->unique();
                $table->string('password')->nullable();
                $table->boolean('is_password_reset')->default(0);
                $table->string('phone_number');
                $table->integer('company_id');
                 $table->unsignedInteger('role_id');
                $table->foreign('role_id')->references('id')->on('roles')->onUpdate('cascade');
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
            Schema::dropIfExists('company_users');
        }catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
    }
}
