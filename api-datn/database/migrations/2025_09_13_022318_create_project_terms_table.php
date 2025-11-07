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
        Schema::create('project_terms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('academy_year_id');
            $table->text('stage');
            $table->text('description');
            $table->text('start_date');
            $table->text('end_date');
            $table->timestamps();
            $table->foreign('academy_year_id')->references('id')->on('academy_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_terms');
    }
};
