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
        Schema::create('postpone_project_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_term_id');
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->default('pending');
            
            $table->foreign('project_term_id')->references('id')->on('project_terms')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
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
        Schema::dropIfExists('postpone_project_terms');
    }
};
