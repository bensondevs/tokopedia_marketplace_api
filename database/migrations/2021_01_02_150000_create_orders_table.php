<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->uuid('id')->primary();

            $table->string('marketplace');
            $table->string('marketplace_logo');
            $table->string('courier_logo');
            $table->string('invoice_num');
            $table->string('order_id');

            $table->string('recipient_name');
            $table->string('shop_name');
            $table->string('address');
            $table->double('weight', 10, 2);
            $table->double('total', 20, 2);
            $table->double('shipping_price', 20, 2);
            $table->string('city');
            $table->string('province');
            $table->string('phone');

            $table->string('invoice_note')->nullable();

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
