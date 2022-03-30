<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->nullable();
            $table->integer('project_id')->nullable();
            $table->decimal('total_quantity',20,2)->default(00.00);
            $table->decimal('total_price',20,2)->default(00.00);
            $table->integer('user_id')->nullable();
            $table->integer('method_id')->nullable();
            $table->string('type')->nullable();
            $table->integer('store_id')->nullable();
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
        Schema::dropIfExists('sells');
    }
}
