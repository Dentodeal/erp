<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_orders', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->bigInteger('representative_id');
            $table->boolean('rate_includes_gst');
            $table->boolean('flood_cess_included');
            $table->boolean('otc');
            $table->bigInteger('created_by_id');
            $table->text('remarks')->nullable();
            $table->string('status');
            $table->string('serial_no');
            $table->bigInteger('pricelist_id');
            $table->bigInteger('warehouse_id');
            $table->dateTime('invoiced_at')->nullable();
            $table->bigInteger('invoiced_by_id')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->string('source');
            $table->string('order_no')->nullable();
            $table->decimal('subtotal',10,2);
            $table->decimal('discount',10,2);
            $table->decimal('freight',10,2);
            $table->decimal('round',8,2);
            $table->decimal('total',10,2);
            $table->boolean('revisit')->default(0);
            $table->string('payment_status');
            $table->boolean('cod');
            $table->decimal('cod_charge',10,2)->default(0.00);
            $table->decimal('paid_amount',10,2)->default(0.00);
            $table->decimal('balance_amount',10,2)->default(0.00);
            $table->bigInteger('quotation_id')->nullable();
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
        Schema::dropIfExists('sale_orders');
    }
}
