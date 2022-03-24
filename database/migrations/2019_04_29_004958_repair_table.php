<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RepairTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repairs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('truck_id');
            $table->string('truck_number');
            $table->integer('dispatcher_id');
            $table->dateTime('placed_in_service');
            $table->dateTime('repair_completed')->nullable();
            $table->dateTime('estimated_repair_date')->nullable();
            $table->string('repair_description')->nullable();
            $table->string('location')->nullable();
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
        Schema::dropIfExists('repairs');
    }
}
