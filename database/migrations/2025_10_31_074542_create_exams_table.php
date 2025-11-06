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
        Schema::create('exams', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title'); // Corresponds to 'EXAM TITLE'
            $table->string('description',200)->nullable(); // Corresponds to 'DESCRIPTION'
            $table->text('instruction')->nullable();
            $table->integer('questions')->default(0); // Corresponds to 'QUESTIONS'
            $table->integer('duration')->nullable(); // Corresponds to 'DURATION' (in minutes, for example)
            $table->integer('pass_mark')->nullable(); 
            $table->boolean('randomize_questions')->default(0);
            $table->boolean('randomize_options')->default(0);
            $table->string('data_status')->default('draft'); // Corresponds to 'STATUS' (e.g., 'draft', 'published', 'archived')
            $table->timestamp('last_modified')->nullable(); // Corresponds to 'LAST MODIFIED'
            $table->timestamps(); // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
