<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('state_id');
            $table->foreign('state_id')->references('id')->on('states');
            $table->string('title', 50);
            $table->integer('iso');
            $table->string('iso_ddd', 6);
            $table->tinyInteger('status');
            $table->string('slug', 75);
            $table->string('order', 5);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cidades');
    }
}
