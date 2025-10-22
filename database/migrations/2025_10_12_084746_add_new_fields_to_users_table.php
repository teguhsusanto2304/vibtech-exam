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
        Schema::table('users', function (Blueprint $table) {
            $table->uuid('work_unit_id')->nullable()->after('email'); // Added after 'email' for logical grouping
            $table->uuid('role_id')->nullable()->after('email');
            // 2. Define the Foreign Key Constraint
            // This ensures data integrity by linking users to existing work units
            $table->foreign('work_unit_id')
                  ->references('id')
                  ->on('work_units')
                  ->onDelete('set null');

            $table->foreign('role_id')
                  ->references('id')
                  ->on('roles')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
