<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DriverTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dispatcher_id');
            $table->string('driver_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('password');
            $table->integer('load_number')->nullable();
            $table->double('last_latitude')->nullable();
            $table->double('last_longitude')->nullable();
            $table->string('truck_number')->nullable();
            $table->boolean('in_use');
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
        Schema::dropIfExists('drivers');
    }
}
