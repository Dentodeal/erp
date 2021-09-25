<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemarksToStockEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_entries', function (Blueprint $table) {
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('created_by_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_entries', function (Blueprint $table) {
            $table->dropColumn('remarks');
            $table->dropColumn('created_by_id');
        });
    }
}
