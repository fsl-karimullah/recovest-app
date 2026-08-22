<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_bills', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('bill_number', 100)->unique();
            $table->string('vendor_name');
            $table->date('bill_date')->index();
            $table->date('due_date')->index();
            $table->decimal('total_amount', 18, 2);
            $table->string('category');
            $table->enum('status', ['PENDING', 'PAID', 'OVERDUE'])->default('PENDING')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_bills');
    }
};
