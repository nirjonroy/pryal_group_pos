<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->integer('company_id')->nullable();
            $table->decimal('project_value',20,2)->default(00.00);
            $table->integer('project_leader')->nullable();
            $table->integer('created_by')->nullable();
            $table->string('start_date',30)->nullable();
            $table->tinyInteger('working_status')->default(0);
            $table->string('end_date',30)->nullable();
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
        Schema::dropIfExists('projects');
    }
}
