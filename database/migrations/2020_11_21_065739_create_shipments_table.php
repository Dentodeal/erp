<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no');
            $table->string('status');
            $table->decimal('length',8,2)->nullable();
            $table->decimal('width',8,2)->nullable();
            $table->decimal('height',8,2)->nullable();
            $table->decimal('weight',8,2)->nullable();
            $table->bigInteger('created_by_id');
            $table->text('tracking')->nullable();
            $table->text('remarks')->nullable();
            $table->bigInteger('sale_order_id');
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
        Schema::dropIfExists('shipments');
    }
}
