<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_mutations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('bank_connection_id')->constrained('bank_connections')->cascadeOnDelete();
            $table->date('transaction_date')->index();
            $table->enum('mutation_type', ['CR', 'DB']); // CR = Kredit/Masuk, DB = Debit/Keluar
            $table->decimal('amount', 18, 2);
            $table->text('description');
            $table->decimal('balance_after', 18, 2)->nullable();
            $table->boolean('is_reconciled')->default(false)->index();
            $table->uuid('reconciled_transaction_id')->nullable();
            $table->json('raw_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_mutations');
    }
};
