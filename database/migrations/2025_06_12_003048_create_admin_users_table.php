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
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->comment('父id');
            $table->integer('level_id')->comment('级别id');
            $table->integer('channel_id')->comment('所属渠道id');
            $table->string('name', 20)->default('')->comment('代理用户名');
            $table->string('account', 50)->default('')->unique('admin_users_name_unique')->comment('账号');
            $table->string('email', 64)->default('')->comment('邮箱');
            $table->string('phone', 24)->default('')->comment('联系方式');
            $table->string('password', 191)->default('');
            $table->string('pwd', 191)->default('');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_cancel')->default(0)->comment('是否已注销 0 未注销 1 已注销 2 注销已通过');
            $table->decimal('balance', 10)->default(0)->comment('余额');
            $table->decimal('recharge', 10)->comment('累计充值');
            $table->decimal('profit', 10)->default(0)->comment('累计利润');
            $table->string('photo', 128)->default('')->comment('图片');
            $table->text('remark')->nullable()->comment('备注');
            $table->rememberToken();
            $table->boolean('is_new')->default(false)->comment('是否新账号 0 未更改过密码的账号  1 已更改过密码的账号');
            $table->boolean('is_relation')->default(true)->comment('是否已联系 1 未联系 2 已联系');
            $table->boolean('type')->default(false)->comment('类型  1 普通类型  2 增强类型');
            $table->integer('person_num')->default(0)->comment('金级增加金级人员数量');
            $table->integer('try_num')->default(0)->comment('试看码数量');
            $table->string('language', 10)->default('')->comment('当前用户选择的语言 zh(中文)  en(英文)  my(马来文)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_admin_users');
    }
};
