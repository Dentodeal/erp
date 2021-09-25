<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id');
            $table->string('type');
            $table->string('payment_via');
            $table->decimal('amount',10,2);
            $table->string('payable_type')->nullable();
            $table->bigInteger('payable_id')->nullable();
            $table->bigInteger('parent_id')->default(0);
            $table->boolean('standalone')->default(false);
            $table->text('reference_id')->nullable();
            $table->text('remarks')->nullable();
            $table->bigInteger('created_by_id');
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
        Schema::dropIfExists('transactions');
    }
}
