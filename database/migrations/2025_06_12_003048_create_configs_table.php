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
        Schema::create('configs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('');
            $table->string('key', 100)->default('')->unique('configs_key_unique');
            $table->string('value', 2048)->default('');
            $table->tinyInteger('type')->default(0);
            $table->string('group', 50)->default('');
            $table->string('remark', 191)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_configs');
    }
};
