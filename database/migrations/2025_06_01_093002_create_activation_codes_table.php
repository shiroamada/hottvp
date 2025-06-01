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
        Schema::create('activation_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->index();
            $table->foreignId('activation_code_preset_id')->constrained('activation_code_presets')->onDelete('cascade');
            $table->foreignId('generated_by_agent_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['available', 'assigned', 'activated', 'expired', 'faulty'])->default('available')->index();
            $table->foreignId('assigned_to_agent_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('activated_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Assuming end users are also in 'users' table or a separate 'customers' table
            $table->decimal('hotcoin_cost_at_generation', 8, 2);
            $table->integer('duration_days_at_generation')->nullable();
            $table->timestamp('generated_at')->useCurrent()->index();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_codes');
    }
};
