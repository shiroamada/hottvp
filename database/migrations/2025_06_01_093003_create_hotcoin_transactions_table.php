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
        Schema::create('hotcoin_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->enum('type', ['code_generation_cost', 'profit_from_downline_sale', 'manual_credit', 'manual_debit', 'initial_balance', 'commission_to_upline']);
            $table->decimal('amount', 12, 2); // Positive for credit, negative for debit
            $table->string('description')->nullable();
            $table->foreignId('related_activation_code_id')->nullable()->constrained('activation_codes')->onDelete('set null');
            $table->foreignId('related_agent_id')->nullable()->constrained('users')->onDelete('set null'); // e.g., downline agent for profit
            $table->timestamp('transaction_date')->useCurrent()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotcoin_transactions');
    }
};
