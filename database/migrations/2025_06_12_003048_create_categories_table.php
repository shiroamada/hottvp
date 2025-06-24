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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->unsignedInteger('pid')->default(0);
            $table->unsignedInteger('model_id')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->string('title', 191)->default('');
            $table->string('keywords', 191)->default('');
            $table->string('description', 191)->default('');
            $table->timestamps();

            $table->unique(['pid', 'name', 'model_id'], 'categories_pid_name_model_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_categories');
    }
};
