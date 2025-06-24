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
        Schema::create('my_levels', function (Blueprint $table) {
            $table->comment('级别管理');
            $table->increments('id');
            $table->string('level_name', 128)->default('')->comment('级别名称');
            $table->decimal('mini_amount', 10)->comment('最低金额');
            $table->integer('try_num')->default(0)->comment('试看码数量');
            $table->integer('ob_try_num')->default(0)->comment('当前生成新用户的人获取试看码数量');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_my_levels');
    }
};
