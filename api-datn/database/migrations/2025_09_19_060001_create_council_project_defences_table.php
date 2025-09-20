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
            $table->unsignedBigInteger('council_project_id');
            $table->unsignedBigInteger('council_member_id');
            $table->text('score')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->foreign('council_project_id')->references('id')->on('council_projects')->onDelete('cascade');
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
        Schema::dropIfExists('council_project_defences');
    }
};
