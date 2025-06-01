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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('upline_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->decimal('hotcoin_balance', 12, 2)->default(0.00);
            $table->decimal('total_profit_earned', 12, 2)->default(0.00);
            $table->boolean('is_admin')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['upline_agent_id']);
            $table->dropColumn(['upline_agent_id', 'hotcoin_balance', 'total_profit_earned', 'is_admin']);
        });
    }
};
