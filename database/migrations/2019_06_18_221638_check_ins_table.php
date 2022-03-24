<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CheckInsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('load_id');
            $table->string('dispatcher_id');
            $table->string('driver_id');
            $table->string('driver_name');
            $table->string('truck_id');
            $table->string('truck_number');
            $table->string('latitude');
            $table->string('longitude');
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
        Schema::dropIfExists('check_ins');
    }
}
