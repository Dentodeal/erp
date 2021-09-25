<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockEntryItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_entry_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('stock_entry_id');
            $table->bigInteger('product_id');
            $table->integer('qty');
            $table->json('expiry_data')->nullable();
            $table->boolean('expirable');
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
        Schema::dropIfExists('stock_entry_items');
    }
}
