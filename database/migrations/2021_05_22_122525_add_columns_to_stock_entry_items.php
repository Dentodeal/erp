<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToStockEntryItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_entry_items', function (Blueprint $table) {
            $table->decimal('length',8,2)->nullable();
            $table->decimal('breadth',8,2)->nullable();
            $table->decimal('height',8,2)->nullable();
            $table->decimal('weight',8,2)->nullable();
            $table->string('gtin')->nullable();
            $table->string('reorder_code')->nullable();
            $table->string('origin_country')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_entry_items', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->dropColumn('breadth');
            $table->dropColumn('height');
            $table->dropColumn('weight');
            $table->dropColumn('gtin');
            $table->dropColumn('reorder_code');
            $table->dropColumn('origin_country');
        });
    }
}
