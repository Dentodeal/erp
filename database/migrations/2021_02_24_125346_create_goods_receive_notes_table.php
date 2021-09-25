<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsReceiveNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_receive_notes', function (Blueprint $table) {
            $table->id();
            $table->string('serial_no');
            $table->bigInteger('supplier_id');
            $table->date('delivery_date');
            $table->text('remarks')->nullable();
            $table->string('status');
            $table->bigInteger('created_by_id');
            $table->bigInteger('warehouse_id');
            $table->bigInteger('purchase_id')->nullable();
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
        Schema::dropIfExists('goods_receive_notes');
    }
}
