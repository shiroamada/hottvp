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
        Schema::create('hot_entities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->string('table_name', 64)->default('')->unique('entities_table_name_unique');
            $table->string('description', 191)->default('');
            $table->unsignedInteger('is_internal')->default(0);
            $table->unsignedInteger('enable_comment')->default(0);
            $table->unsignedTinyInteger('is_show_content_manage')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_entities');
    }
};
