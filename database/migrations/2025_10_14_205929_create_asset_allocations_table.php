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
        Schema::create('asset_allocations', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            
            // Foreign key linking to the InventoryAsset
            $table->uuid('inventory_asset_id');
            $table->uuid('allocated_to_user_id')->nullable();
            $table->uuid('allocated_to_work_unit_id')->nullable();

            // Location details, e.g., 'Room 305'
            $table->string('location_detail')->nullable();
            
            // Allocation Dates
            $table->date('allocation_date')->comment('The date the asset was assigned.');
            $table->date('return_date')->nullable()->comment('The date the asset was returned (null if currently allocated).');

            // Notes about the allocation
            $table->text('notes')->nullable();
            $table->tinyInteger('data_status')->default(1);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('inventory_asset_id')
                  ->references('id')
                  ->on('inventory_assets')
                  ->onDelete('cascade');

            $table->foreign('allocated_to_user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('allocated_to_work_unit_id')
                  ->references('id')
                  ->on('work_units')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_allocations');
    }
};
