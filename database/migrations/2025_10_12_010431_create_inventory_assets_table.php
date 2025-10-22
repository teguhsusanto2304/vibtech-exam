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
        Schema::create('inventory_assets', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->string('path_image')->nullable();
            $table->foreignUuid('category_id')->constrained('inventory_asset_categories');
            $table->string('item_code',10)->nullable();
            $table->string('item_name',150);
            $table->string('item_specification',200)->nullable();
            $table->string('item_brand',150);
            $table->tinyInteger('item_condition')->default(1);
            $table->date('received_date')->nullable();
            $table->tinyInteger('data_status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_assets');
    }
};
