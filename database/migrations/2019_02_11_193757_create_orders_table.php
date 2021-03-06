<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('store_id');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->unsignedInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->string('client_ip', 100)->nullable();
            $table->string('payment', 10)->nullable();
            $table->decimal('freight', 8, 2);
            $table->string('client_name', 200)->nullable();
            $table->string('client_cpf', 15)->nullable();
            $table->string('client_phone', 15)->nullable();
            $table->unsignedInteger('client_city_id')->nullable();
            $table->foreign('client_city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedInteger('client_district_id')->nullable();
            $table->foreign('client_district_id')->references('id')->on('districts')->onDelete('cascade');
            $table->string('client_cep', 10)->nullable();
            $table->string('client_street', 200)->nullable();
            $table->string('client_number', 15)->nullable();
            $table->string('client_complement', 100)->nullable();
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
        Schema::dropIfExists('orders');
    }
}
