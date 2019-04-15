<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->decimal('min_parcel_price', 8, 2)->nullable();
            $table->integer('max_parcel')->nullable();
            $table->integer('max_product_unit')->nullable();
            $table->char('cnpj', 18)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('image_cover_desktop', 300)->nullable();
            $table->string('image_cover_mobile', 300)->nullable();
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
