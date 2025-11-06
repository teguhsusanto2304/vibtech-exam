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
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('exam_id');
            $table->uuid('question_id');
            $table->integer('order')->nullable(); // optional for sorting
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('exam_id')
                ->references('id')->on('exams')
                ->cascadeOnDelete();

            $table->foreign('question_id')
                ->references('id')->on('questions')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_questions');
    }
};
