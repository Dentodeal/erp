<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDentodealOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dentodeal_orders', function (Blueprint $table) {
            $table->id();
            $table->json('customer_data');
            $table->string('order_no');
            $table->string('payment_method');
            $table->decimal('subtotal',10,2);
            $table->string('customer_email');
            $table->string('customer_name');
            $table->decimal('total',10,2);
            $table->decimal('shipping_charge',10,2)->nullable();
            $table->decimal('cod_charge',10,2)->nullable();
            $table->json('extra_data')->nullable();
            $table->string('status');
            $table->string('erp_status')->default('Draft');
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
        Schema::dropIfExists('dentodeal_orders');
    }
}
