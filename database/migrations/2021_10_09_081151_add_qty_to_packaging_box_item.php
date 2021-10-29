<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtyToPackagingBoxItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packaging_box_item', function (Blueprint $table) {
            $table->integer('qty');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packaging_box_item', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
}
