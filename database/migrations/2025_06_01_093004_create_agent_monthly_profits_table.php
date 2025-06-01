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
        Schema::create('agent_monthly_profits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('users')->onDelete('cascade');
            $table->string('month_year', 7)->comment('Format: YYYY-MM'); // e.g., 2025-06
            $table->decimal('profit_amount', 12, 2);
            $table->timestamps();

            $table->unique(['agent_id', 'month_year']);
            $table->index('month_year');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_monthly_profits');
    }
};
