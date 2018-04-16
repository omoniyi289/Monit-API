<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashBankDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_bank_deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amount');
            $table->string('teller_number')->nullable();
            $table->string('pos_receipt_number')->nullable();
            $table->string('pos_receipt_range')->nullable();
            $table->integer('created_by');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');          
            $table->string('date')->nullable();
            $table->string('bank')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('account_number')->nullable();
            $table->string('payment_type')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onUpdate('cascade');
            $table->foreign('station_id')->references('id')->on('stations')->onUpdate('cascade');
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
        Schema::dropIfExists('cash_bank_deposits');
    }
}
