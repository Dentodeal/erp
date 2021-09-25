<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaleReturnItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_return_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->boolean('expirable');
            $table->decimal('gst',10,2);
            $table->integer('qty');
            $table->decimal('rate',10,2);
            $table->boolean('is_free')->default(false);
            $table->decimal('taxable',10,2)->nullable();
            $table->decimal('tax_amount',10,2)->nullable();
            $table->decimal('total',10,2);
            $table->bigInteger('sale_return_id');
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
        Schema::dropIfExists('sale_return_items');
    }
}
