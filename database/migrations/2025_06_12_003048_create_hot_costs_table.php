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
        Schema::create('hot_costs', function (Blueprint $table) {
            $table->comment('国代级别成本管理');
            $table->increments('id');
            $table->string('user_id', 128)->default('')->comment('级别id');
            $table->string('level_id', 128)->default('')->comment('级别id');
            $table->decimal('mini_amount', 10)->comment('最低金额');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_costs');
    }
};
