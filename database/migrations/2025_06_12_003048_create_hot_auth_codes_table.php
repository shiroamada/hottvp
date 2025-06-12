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
        Schema::create('hot_auth_codes', function (Blueprint $table) {
            $table->comment('授权码管理');
            $table->increments('id');
            $table->integer('assort_id')->comment('所属类型id');
            $table->integer('user_id')->comment('用户id（创建人id）');
            $table->string('auth_code', 128)->default('')->comment('授权码');
            $table->text('remark')->nullable()->comment('备注');
            $table->unsignedTinyInteger('status')->default(0)->comment('授权码状态 0 未使用 1 已使用 2 已到期 3 已取消');
            $table->boolean('type')->default(false)->comment('类型  1 普通类型  2 增强类型');
            $table->boolean('is_try')->default(true)->comment('是否试看码 1 否 2 是');
            $table->decimal('profit', 10, 0)->default(0)->comment('管理员利润');
            $table->timestamp('expire_at')->nullable()->comment('到期时间');
            $table->integer('num')->default(0)->comment('生成授权码数量');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_auth_codes');
    }
};
