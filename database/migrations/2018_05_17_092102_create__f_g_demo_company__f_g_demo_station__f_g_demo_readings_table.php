<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFGDemoCompanyFGDemoStationFGDemoReadingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('FGdemo_companies', function (Blueprint $table) {
                $table->integer('id');
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('registration_number')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->string('address')->nullable();
                $table->integer('user_id')->nullable();
                $table->string('logo')->nullable();
                $table->timestamps();
                $table->softDeletes();
        });
        Schema::create('FGdemo_stations', function (Blueprint $table) {
                $table->increments('id');
                $table->string("name");
                $table->string("address")->nullable();
                $table->string("opening_time")->nullable();;
                $table->string("manager_name")->nullable();;
                $table->string("manager_phone")->nullable();;
                $table->string("manager_email")->nullable();;
                $table->string("city");
                $table->string("state");
                $table->string("daily_budget")->nullable();;
                $table->string("monthly_budget")->nullable();;
                $table->string("license_type")->nullable();;
                $table->string("expenses_type")->nullable();
                $table->string("company_id");
                $table->integer("station_user_id")->nullable();;
                $table->integer("is_station_enabled")->default(1);
                $table->timestamps();
                $table->softDeletes();
        });

        Schema::create('FGdemo_daily_stock_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('tank_id');
            $table->string("tank_code")->nullable();
            $table->decimal("phy_shift_start_volume_reading", 20, 2)->nullable();
            $table->decimal("phy_shift_end_volume_reading", 20, 2)->nullable();
            $table->decimal("atg_shift_start_volume_reading", 20, 2)->nullable();
            $table->decimal("atg_shift_end_volume_reading", 20, 2)->nullable();
            $table->decimal("start_delivery", 20, 2)->nullable();
            $table->decimal("end_delivery", 20, 2)->nullable();
            $table->decimal("return_to_tank", 20, 2)->nullable();
            $table->string("reading_date")->nullable();
            $table->string("product")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->integer("last_modified_by")->nullable();
            $table->string("status")->nullable();
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('FGdemo_daily_totalizer_readings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('company_id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('pump_id')->nullable();
            $table->string("pump_number")->nullable();
            $table->string("nozzle_code")->nullable();
            
            $table->decimal("open_shift_totalizer_reading", 20, 2)->nullable();
            $table->decimal("close_shift_totalizer_reading", 20, 2)->nullable();
            $table->decimal("shift_1_totalizer_reading", 20, 2)->nullable();
            $table->decimal("shift_2_totalizer_reading", 20, 2)->nullable();
            $table->decimal("shift_1_cash_collected", 20, 2)->nullable();
            $table->decimal("shift_2_cash_collected", 20, 2)->nullable();
            $table->decimal("cash_collected", 20, 2)->nullable();
            $table->string("product")->nullable();
            $table->decimal("ppv", 20, 2)->nullable();

            $table->string("reading_date")->nullable();
            $table->unsignedInteger("created_by")->nullable();
            $table->integer("last_modified_by")->nullable();
            $table->string("status")->nullable();
        
            $table->timestamps();$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('FGdemo_companies');
         Schema::dropIfExists('FGdemo_stations');
          Schema::dropIfExists('FGdemo_daily_totalizer_readings');
           Schema::dropIfExists('FGdemo_daily_stock_readings');
    }
}
