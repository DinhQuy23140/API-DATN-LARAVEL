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
        Schema::create('council_project_defences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('council_id');
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('reviewer_id');
            $table->text('room');
            $table->date('date');
            $table->time('time');
            $table->timestamps();

            $table->foreign('council_id')->references('id')->on('councils')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('reviewer_id')->references('id')->on('supervisors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('council_project_defences');
    }
};
