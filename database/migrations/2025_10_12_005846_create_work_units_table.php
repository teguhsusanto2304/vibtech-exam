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
        Schema::create('work_units', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->uuid('parent_id')->nullable(); 

            // Unit Name
            $table->string('unit_name', 100);

            // Data Status (e.g., 'Active', 'Inactive')
            $table->tinyInteger('data_status')->default(1);
            $table->timestamps();

            // Timestamps (created_at and updated_at)

            // Define the Foreign Key Constraint
            // Ensures parent_id references the 'id' column on the same table
            $table->foreign('parent_id')
                ->references('id') 
                ->on('work_units')
                ->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_units');
    }
};
