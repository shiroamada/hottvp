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
        Schema::create('hot_assort_levels', function (Blueprint $table) {
            $table->comment('级别配套管理');
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('assort_id')->comment('配套id');
            $table->integer('level_id')->comment('级别名称');
            $table->decimal('money', 9)->default(0)->comment('成本');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_assort_levels');
    }
};
