<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToQuotationTemplates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_templates', function (Blueprint $table) {
            $table->json('bank')->nullable();
            $table->string('type')->nullable();
            $table->decimal('prev_balance',10,2)->default(0);
            $table->decimal('bank_charges',10,2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_templates', function (Blueprint $table) {
            $table->dropColumn('bank');
            $table->dropColumn('type');
            $table->dropColumn('bank_charges');
            $table->dropColumn('prev_balance');
        });
    }
}
