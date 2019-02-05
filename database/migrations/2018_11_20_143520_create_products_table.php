<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id')->nullable();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->string('identifier', 15)->unique()->nullable();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->unique()->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('old_price', 8, 2)->nullable();
            $table->integer('pageviews')->default(1);
            $table->integer('status')->default(2);
            $table->string('description', 2000)->nullable();
            $table->integer('gender')->nullable();
            $table->string('related', 200)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Full Text Index
        DB::statement('ALTER TABLE products ADD FULLTEXT fulltext_index (title, identifier)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
