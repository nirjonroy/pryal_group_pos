<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasePaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_id')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->string('invoice_no',50)->nullable();
            $table->decimal('payment_amount',20,2)->default(00.00);
            $table->integer('payment_method_id')->nullable();
            $table->text('description')->nullable();
            $table->string('note',150)->nullable();
            $table->integer('paid_by')->nullable();
            $table->string('deleted_at',30)->nullable();
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
        Schema::dropIfExists('purchase_payment_histories');
    }
}
