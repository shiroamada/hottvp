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
        Schema::create('my_menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->default('')->unique('menus_name_unique');
            $table->unsignedInteger('pid')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_lock_name')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->string('route', 100)->default('')->unique('menus_route_unique');
            $table->string('url', 512)->default('');
            $table->string('group', 50)->default('');
            $table->string('guard_name', 50)->default('admin');
            $table->string('remark', 191)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_my_menus');
    }
};
