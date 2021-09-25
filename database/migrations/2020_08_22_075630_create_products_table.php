<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('status');
            $table->string('name')->unique();
            $table->string('alias')->nullable();
            $table->string('sku')->unique();
            $table->boolean('enabled');
            $table->string('hsn')->nullable();
            $table->string('reorder_code')->nullable();
            $table->decimal('weight',8,2)->default(0.00);
            $table->decimal('mrp',10,2)->default(0.00);
            $table->decimal('gst',8,2);
            $table->decimal('gsp_customer',10,2)->default(0.00);
            $table->decimal('gsp_dealer',10,2)->default(0.00);
            $table->bigInteger('type_id');
            $table->bigInteger('department_id');
            $table->bigInteger('category_id');
            $table->bigInteger('sub_category_id');
            $table->bigInteger('brand_id');
            $table->boolean('expirable')->default(0);
            $table->text('description')->nullable();
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
        Schema::dropIfExists('products');
    }
}
