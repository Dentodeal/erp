<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no');
            $table->string('status');
            $table->bigInteger('created_by_id');
            $table->bigInteger('representative_id');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('pricelist_id');
            $table->bigInteger('customer_id');
            $table->date('valid_until');
            $table->text('instructions')->nullable();
            $table->string('po_number')->nullable();
            $table->string('fob_point')->nullable();
            $table->text('terms')->nullable();
            $table->text('remarks')->nullable();
            $table->decimal('subtotal',10,2);
            $table->decimal('discount',10,2);
            $table->decimal('round',10,2);
            $table->decimal('freight',10,2);
            $table->decimal('total',10,2);
            $table->boolean('rate_includes_gst');
            $table->boolean('flood_cess_included');
            $table->string('contact_person')->nullable();
            $table->string('contact_phone');
            $table->string('contact_email');
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
        Schema::dropIfExists('quotations');
    }
}
