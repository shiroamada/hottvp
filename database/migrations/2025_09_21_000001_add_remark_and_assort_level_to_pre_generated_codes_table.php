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
            $table->text('remark')->nullable()->after('vendor');
            $table->unsignedInteger('assort_level_id')->nullable()->after('remark');

            $table->foreign('assort_level_id')->references('id')->on('assort_levels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pre_generated_codes', function (Blueprint $table) {
            $table->dropForeign(['assort_level_id']);
            $table->dropColumn(['remark', 'assort_level_id']);
        });
    }
};