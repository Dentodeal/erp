<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTemplateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_template_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->string('product_name')->nullable();
            $table->decimal('gst',10,2);
            $table->integer('qty');
            $table->decimal('rate',10,2);
            $table->boolean('is_free')->default(false);
            $table->decimal('taxable',10,2)->nullable();
            $table->decimal('tax_amount',10,2)->nullable();
            $table->decimal('total',10,2);
            $table->bigInteger('quotation_template_id');
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
        Schema::dropIfExists('quotation_template_items');
    }
}
