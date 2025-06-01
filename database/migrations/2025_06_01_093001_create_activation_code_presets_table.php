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
        Schema::create('activation_code_presets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type_identifier')->unique();
            $table->decimal('hotcoin_cost', 8, 2);
            $table->integer('duration_days')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activation_code_presets');
    }
};
