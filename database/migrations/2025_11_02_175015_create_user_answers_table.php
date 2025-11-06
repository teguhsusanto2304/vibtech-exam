<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_answers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_exam_id');
            $table->uuid('exam_question_id');
            $table->string('user_option'); // The option chosen by the user (A, B, C, D)
            $table->boolean('is_correct')->default(false);
            $table->timestamps();

             $table->foreign('user_exam_id')
                ->references('id')->on('user_exams')
                ->cascadeOnDelete();
            $table->foreign('exam_question_id')
                ->references('id')->on('exam_questions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_answers');
    }
};
