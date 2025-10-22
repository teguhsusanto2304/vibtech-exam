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
        Schema::create('incident_actions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('incident_report_id');
            $table->uuid('action_taken_by_id');

            // Action Details
            $table->tinyInteger('action_type')->default(1); // e.g., Maintenance Scheduled, Investigation Started
            $table->text('details');
            $table->dateTime('action_date');
            $table->timestamps();

            $table->foreign('incident_report_id')
                  ->references('id')
                  ->on('incident_reports')
                  ->onDelete('cascade');
                  
            $table->foreign('action_taken_by_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_actions');
    }
};
