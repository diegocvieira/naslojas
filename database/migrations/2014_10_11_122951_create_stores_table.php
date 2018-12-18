<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->string('name', 200)->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('street', 200)->nullable();
            $table->string('number', 15)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('slug', 200)->unique()->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('reserve')->default(1);
            $table->timestamps();
        });

        // Full Text Index
        DB::statement('ALTER TABLE stores ADD FULLTEXT fulltext_index (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stores');
    }
}
