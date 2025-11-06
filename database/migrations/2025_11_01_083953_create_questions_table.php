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
        Schema::create('questions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('question_stem');
            $table->string('topic')->nullable();
            $table->tinyInteger('difficulty_level')->nullable();
            $table->string('option_a');
            $table->string('option_b');
            $table->string('option_c');
            $table->string('option_d');
            $table->string('correct_option'); // To store which option is correct (e.g., 'A', 'B', 'C', 'D')
            $table->text('explanation')->nullable(); // For "Explanation for Correct Answer"
            $table->string('data_status',10)->default('active')->index(); // active, inactive, deleted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
