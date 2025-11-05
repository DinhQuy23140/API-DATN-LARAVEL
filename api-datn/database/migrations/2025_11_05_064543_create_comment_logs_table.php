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
        Schema::create('comment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('progress_log_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->text('content');
            $table->foreign('progress_log_id')->references('id')->on('progress_logs')->onDelete('cascade');
            $table->foreign('supervisor_id')->references('id')->on('supervisors')->onDelete('cascade');
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
        Schema::dropIfExists('comment_logs');
    }
};
