<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToQuotationTemplateItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_template_items', function (Blueprint $table) {
            $table->string('mask_name')->nullable();
            $table->boolean('use_mask')->default(0);
            $table->dropColumn('is_free');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_template_items', function (Blueprint $table) {
            $table->dropColumn('mask_name');
            $table->dropColumn('use_mask');
            $table->boolean('is_free')->default(false);
        });
    }
}
