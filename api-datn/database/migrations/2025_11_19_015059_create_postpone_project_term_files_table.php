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
        Schema::create('postpone_project_term_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('postpone_project_term_id');
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type');
            $table->foreign('postpone_project_term_id')->references('id')->on('postpone_project_terms')->onDelete('cascade');
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
        Schema::dropIfExists('postpone_project_term_files');
    }
};
