<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fixed_assets', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('asset_code', 50)->unique();
            $table->string('asset_name');
            $table->date('purchase_date');
            $table->decimal('purchase_cost', 18, 2);
            $table->decimal('salvage_value', 18, 2)->default(0);
            $table->integer('useful_life_years')->default(5); // Umur Ekonomis
            $table->decimal('accumulated_depreciation', 18, 2)->default(0);
            $table->decimal('book_value', 18, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fixed_assets');
    }
};
