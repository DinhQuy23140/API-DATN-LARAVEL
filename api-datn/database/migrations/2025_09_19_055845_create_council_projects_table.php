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
        Schema::create('council_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('council_id');
            $table->unsignedBigInteger('assignment_id');
            $table->unsignedBigInteger('council_member_id')->nullable();
            $table->text('room')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();

            $table->foreign('council_id')->references('id')->on('councils')->onDelete('cascade');
            $table->foreign('assignment_id')->references('id')->on('assignments')->onDelete('cascade');
            $table->foreign('council_member_id')->references('id')->on('council_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('council_projects');
    }
};
