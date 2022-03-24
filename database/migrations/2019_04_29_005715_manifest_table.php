<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ManifestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('load_id');
            $table->string('uploaded_by');
            $table->string('document_name');
            $table->string('path');
            $table->dateTime('upload_date');
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
        Schema::dropIfExists('manifests');
    }
}
