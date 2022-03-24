<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LoadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('load_number');
            $table->integer('dispatcher_id');
            $table->integer('driver_id')->nullable();
            $table->integer('truck_id')->nullable();
            $table->string('company')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('po_number')->nullable();
            $table->dateTime('pickup_date')->nullable();
            $table->dateTime('delivery_date')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('delivery_address')->nullable();
            $table->double('price')->nullable();
            $table->string('contact')->nullable();
            $table->string('type')->nullable();
            $table->string('load_notes')->nullable();
            $table->string('delivery_notes')->nullable();
            $table->string('load_feedback')->nullable();
            $table->string('load_file')->nullable();
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
        Schema::dropIfExists('loads');
    }
}
