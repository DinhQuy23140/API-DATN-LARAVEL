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
        Schema::create('stage_timelines', function (Blueprint $table) {
            $table->id();

            // FK đồng bộ với project_terms.id (mặc định là unsignedBigInteger)
            $table->unsignedBigInteger('project_term_id');

            // Đây là số vòng (1 -> 8), nên dùng tinyInteger cho nhẹ
            $table->unsignedTinyInteger('number_of_rounds');

            // Các field khác nên là date thay vì text
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();

            // Foreign key
            $table->foreign('project_term_id')
                ->references('id')->on('project_terms')
                ->onDelete('cascade');

            // Unique constraint
            $table->unique(['project_term_id', 'number_of_rounds']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stage_timelines');
    }
};
