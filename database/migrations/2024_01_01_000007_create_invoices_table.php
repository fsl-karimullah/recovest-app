<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->string('invoice_number', 100)->unique();
            $table->string('client_name');
            $table->string('client_email')->nullable();
            $table->date('issue_date')->index();
            $table->date('due_date')->index();
            $table->decimal('subtotal', 18, 2);
            $table->decimal('tax_rate', 5, 2)->default(11.00); // PPN 11%
            $table->decimal('tax_amount', 18, 2)->default(0);
            $table->decimal('total_amount', 18, 2);
            $table->enum('status', ['DRAFT', 'UNPAID', 'PAID', 'OVERDUE'])->default('UNPAID')->index();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
