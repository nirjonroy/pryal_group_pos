<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->nullable();
            $table->integer('supplier_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->decimal('total_quantity',20,2)->default(00.00);
            $table->decimal('total_price',20,2)->default(00.00);
            $table->string('payment_status',20)->default('Un-paid');
            $table->tinyInteger('status')->default(0);
            $table->text('description')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('purchases');
    }
}
