<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpenseHeaderAndExpenseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expense_header', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('total_amount', 11, 2)->nullable();
            $table->string('expense_code')->nullable();
            $table->integer('created_by')->nullable();
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');          
            $table->string('expense_date')->nullable();
            $table->string('v1_id')->nullable();
            //$table->string('expense_type')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('expense_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('expense_id')->nullable();
            $table->integer('created_by')->nullable();
            $table->decimal('unit_amount', 11, 2)->nullable();
            $table->decimal('total_amount', 11, 2)->nullable();
            $table->string('quantity')->nullable();
            $table->string('expense_type')->nullable();
            $table->string('proof_of_payment')->nullable();
            $table->string('approved')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_description', 500)->nullable();
            $table->string('v1_id')->nullable();
         
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expense_header');
        Schema::dropIfExists('expense_items');
    }
}
