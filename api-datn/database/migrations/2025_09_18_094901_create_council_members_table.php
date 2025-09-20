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
        Schema::create('council_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('council_id');
            $table->unsignedBigInteger('supervisor_id');
            $table->string('role')->default('5'); // 1: Chủ tịch, 2: Thư ký, 3: Ủy viên 1, 4: Ủy viên 2, 5: Ủy viên 3
            $table->foreign('council_id')->references('id')->on('councils')->onDelete('cascade');
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
        Schema::dropIfExists('council_members');
    }
};
