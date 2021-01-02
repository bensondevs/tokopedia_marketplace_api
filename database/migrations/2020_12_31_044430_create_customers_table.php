<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->string('invoice_num')->primary();
            $table->string('order_id');
            $table->string('fs_id');
            $table->string('customer_account_name');
            $table->string('recipient_name')->nullable();
            $table->string('recipient_address')->nullable();
            $table->string('recipient_district')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->string('recipient_city');
            $table->string('recipient_province');

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
        Schema::dropIfExists('customers');
    }
}
