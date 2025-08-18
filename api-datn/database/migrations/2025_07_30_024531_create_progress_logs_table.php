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
        Schema::create('progress_logs', function (Blueprint $table) {
            $table->id();
            $table->string('process_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_date_time')->nullable();
            $table->dateTime('end_date_time')->nullable();
            $table->text('instructor_comment')->nullable();
            $table->string('student_status')->default('chua_bat_dau');     // Enum-like string
            $table->string('instructor_status')->default('chua_danh_gia'); // Enum-like string
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
        Schema::dropIfExists('progress_logs');
    }
};
