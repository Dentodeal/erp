<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToSaleOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->string('mask_name')->nullable();
            $table->boolean('use_mask')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_order_items', function (Blueprint $table) {
            $table->dropColumn('mask_name');
            $table->dropColumn('use_mask');
        });
    }
}
