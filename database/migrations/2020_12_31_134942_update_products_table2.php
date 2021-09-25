<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateProductsTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('dentodeal_enabled')->default(0);
            $table->string('dentodeal_sku')->nullable();
            $table->boolean('dentodeal_bundled')->default(0);
            $table->integer('dentodeal_bundle_qty')->default(0);
            $table->string('dentodeal_frontend_url')->nullable();
            $table->bigInteger('dentodeal_id')->nullable();
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
            $table->dropColumn('dentodeal_enabled');
            $table->dropColumn('dentodeal_sku');
            $table->dropColumn('dentodeal_bundled');
            $table->dropColumn('dentodeal_bundle_qty');
            $table->dropColumn('dentodeal_frontend_url');
            $table->dropColumn('dentodeal_id');
        });
    }
}
