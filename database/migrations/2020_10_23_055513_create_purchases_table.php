<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_id');
            $table->string('bill_number');
            $table->date('bill_date');
            $table->date('delivery_date');
            $table->string('status');
            $table->bigInteger('created_by_id');
            $table->bigInteger('warehouse_id');
            $table->decimal('subtotal',10,2);
            $table->decimal('bill_total',10,2);
            $table->decimal('grand_total',10,2);
            $table->decimal('discount_subtotal',10,2)->nullable();
            $table->decimal('bill_freight',10,2)->nullable();
            $table->decimal('external_freight',10,2)->nullable();
            $table->boolean('bill_freight_includes_gst');
            $table->decimal('bill_freight_gst',8,2)->nullable();
            $table->decimal('round',10,2)->nullable();
            $table->string('row_discount_mode');
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
