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
        Schema::table('pre_generated_codes', function (Blueprint $table) {
            $table->string('type')->nullable()->after('code');
            $table->string('vendor')->nullable()->after('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_generated_codes', function (Blueprint $table) {
            $table->dropColumn(['type', 'vendor']);
        });
    }
};