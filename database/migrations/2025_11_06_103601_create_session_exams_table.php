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
        Schema::create('session_exams', function (Blueprint $table) {
            Schema::create('user_exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Foreign keys
             $table->uuid('user_id');
             $table->uuid('exam_id');

            // Exam Status and Timing
            $table->string('data_status')->default('pending')->index(); // pending, started, completed
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->double('scores')->default(0.00);
            $table->integer('durations')->default(0); //in minutes
            $table->integer('attempts_used')->default(0);
            $table->timestamp('active_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')->on('users')
                ->cascadeOnDelete();

            $table->foreign('exam_id')
                ->references('id')->on('exams')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_exams');
    }
};
