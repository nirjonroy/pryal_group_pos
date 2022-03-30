<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_payment_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id')->nullable();
            $table->integer('project_id')->nullable();
            $table->decimal('payment_amount',20,2)->default(00.00);
            $table->integer('payment_method_id')->nullable();
            $table->text('description')->nullable();
            $table->string('note',150)->nullable();
            $table->integer('payment_accepted_by')->nullable();
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
        Schema::dropIfExists('project_payment_histories');
    }
}
