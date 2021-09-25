<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->decimal('taxable_discount',10,2)->default(0);
            $table->decimal('taxable_total',10,2);
            $table->decimal('tax_total',10,2);
            $table->decimal('tax_row_total',10,2);
            $table->string('freight_split_method')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropColumn('taxable_discount');
            $table->dropColumn('taxable_total');
            $table->dropColumn('tax_total');
            $table->dropColumn('tax_row_total');
            $table->dropColumn('freight_split_method');
        });
    }
}
