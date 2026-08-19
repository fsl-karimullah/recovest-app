<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations');
            $table->string('transaction_number', 100)->unique();
            $table->date('transaction_date')->index();
            $table->enum('type', ['INCOME', 'EXPENSE', 'TRANSFER'])->index();
            $table->decimal('amount', 18, 2);
            $table->foreignUuid('chart_of_account_id')->constrained('chart_of_accounts');
            $table->foreignUuid('bank_connection_id')->nullable()->constrained('bank_connections')->nullOnDelete();
            $table->string('contact_name')->nullable(); // Vendor / Klien
            $table->string('reference_number')->nullable();
            $table->string('category');
            $table->text('description')->nullable();
            $table->string('proof_attachment_path')->nullable();
            $table->enum('status', ['DRAFT', 'COMPLETED', 'VOID'])->default('COMPLETED');
            $table->foreignUuid('created_by')->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
