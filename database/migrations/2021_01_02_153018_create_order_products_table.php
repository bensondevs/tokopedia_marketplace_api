<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_products', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('order_id');
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onDelete('CASCADE');
            $table->string('product_name');
            $table->string('sku');
            $table->string('size');
            $table->integer('quantity');

            $table->string('notes')->nullable();

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
        Schema::dropIfExists('order_products');
    }
}
