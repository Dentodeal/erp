<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_id');
            $table->bigInteger('product_id');
            $table->string('hsn')->nullable();
            $table->boolean('expirable');
            $table->decimal('weight',10,2);
            $table->decimal('mrp',10,2)->nullable();
            $table->decimal('gst',8,2);
            $table->integer('qty');
            $table->boolean('is_free');
            $table->boolean('local');
            $table->decimal('rate',10,2);
            $table->decimal('discount',10,2)->nullable();
            $table->decimal('taxable',10,2);
            $table->decimal('tax_amount',10,2);
            $table->decimal('total',10,2);
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
        Schema::dropIfExists('purchase_items');
    }
}
