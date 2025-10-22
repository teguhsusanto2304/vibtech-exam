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
        Schema::create('incident_reports', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('path_image')->nullable();
            $table->uuid('inventory_asset_id');
            $table->bigInteger('reported_by_id'); // Nullable for anonymous reports

            // Incident Details
            $table->dateTime('incident_date');
            $table->string('title');
            $table->text('description');
            $table->tinyInteger('severity')->default(1); // e.g., Low, High, Critical
            $table->tinyInteger('data_status')->default(1); // e.g., Reported, Investigating, Resolved
            $table->dateTime('resolved_at')->nullable();
            $table->text('resolution_details')->nullable();
            $table->timestamps();

            $table->foreign('inventory_asset_id')
                  ->references('id')
                  ->on('inventory_assets')
                  ->onDelete('cascade');

            $table->foreign('reported_by_id')
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
        Schema::dropIfExists('incident_reports');
    }
};
