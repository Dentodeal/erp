<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_cost', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->decimal('min_margin',10,2)->nullable();
            $table->decimal('landing_price',10,2)->nullable();
            $table->decimal('previous_landing_price',10,2)->nullable();
            $table->decimal('cost_price',10,2)->nullable();
            $table->decimal('previous_cost_price',10,2)->nullable();
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
        Schema::dropIfExists('product_cost');
    }
}
