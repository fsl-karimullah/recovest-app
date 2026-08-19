<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_connections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('organization_id')->constrained('organizations')->cascadeOnDelete();
            $table->foreignUuid('chart_of_account_id')->constrained('chart_of_accounts');
            $table->string('bank_name');
            $table->string('account_number', 50);
            $table->string('account_holder_name');
            $table->enum('connection_status', ['CONNECTED', 'DISCONNECTED', 'SYNCING', 'ERROR'])->default('CONNECTED');
            $table->timestamp('last_synced_at')->nullable();
            $table->boolean('is_dummy')->default(true);
            $table->json('credentials_payload')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_connections');
    }
};
