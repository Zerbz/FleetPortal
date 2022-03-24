<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TruckTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trucks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispatcher_id');
            $table->integer('driver_id')->nullable();
            $table->string('truck_number');
            $table->string('make');
            $table->string('model');
            $table->string('year');
            $table->string('mileage');
            $table->string('plate_number');
            $table->boolean('in_service');
            $table->boolean('in_use');
            $table->string('truck_notes')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('trucks');
    }
}
