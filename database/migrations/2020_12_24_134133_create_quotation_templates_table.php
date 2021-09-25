<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->bigInteger('created_by_id');
            $table->bigInteger('representative_id')->nullable();
            $table->bigInteger('warehouse_id')->nullable();
            $table->bigInteger('pricelist_id')->nullable();
            $table->bigInteger('customer_id')->nullable();
            $table->date('valid_until')->nullable();
            $table->text('instructions')->nullable();
            $table->string('po_number')->nullable();
            $table->string('fob_point')->nullable();
            $table->text('terms')->nullable();
            $table->text('remarks')->nullable();
            $table->decimal('subtotal',10,2)->nullable();
            $table->decimal('discount',10,2)->nullable();
            $table->decimal('round',10,2)->nullable();
            $table->decimal('freight',10,2)->nullable();
            $table->decimal('total',10,2)->nullable();
            $table->boolean('rate_includes_gst')->nullable();
            $table->boolean('flood_cess_included')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('ship_via')->nullable();
            $table->date('ship_date')->nullable();
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
        Schema::dropIfExists('quotation_templates');
    }
}
