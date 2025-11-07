<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('project_term_id')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('counter_argument_id')->nullable();
            $table->text('counter_argument_status')->nullable();
            $table->text('counter_argument_comment')->nullable();
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
            $table->foreign('project_term_id')->references('id')->on('project_terms')->onDelete('cascade');
            $table->foreign('counter_argument_id')->references('id')->on('supervisors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assignments');
    }
};
