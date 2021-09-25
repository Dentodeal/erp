<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('length',8,2)->nullable();
            $table->decimal('breadth',8,2)->nullable();
            $table->decimal('height',8,2)->nullable();
            $table->string('gtin')->nullable();
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->dropColumn('breadth');
            $table->dropColumn('height');
            $table->dropColumn('gtin');
            $table->dropColumn('origin_country');
        });
    }
}
