<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoodsReceiveNoteItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_receive_note_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('product_id');
            $table->integer('qty');
            $table->date('expiry_date')->nullable();
            $table->boolean('expirable');
            $table->bigInteger('grn_id');
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
        Schema::dropIfExists('goods_receive_note_items');
    }
}
