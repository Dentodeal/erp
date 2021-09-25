<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDentodealOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dentodeal_order_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('rate',10,2);
            $table->integer('qty');
            $table->decimal('total',10,2);
            $table->string('sku');
            $table->bigInteger('dentodeal_order_id');
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
        Schema::dropIfExists('dentodeal_order_items');
    }
}
