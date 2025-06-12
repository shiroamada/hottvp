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
        Schema::create('hot_huobis', function (Blueprint $table) {
            $table->comment('火币记录明细表');
            $table->increments('id');
            $table->unsignedInteger('user_id')->default(0)->comment('用户id');
            $table->string('event')->default('')->comment('事件');
            $table->decimal('money', 10)->default(0)->comment('金额');
            $table->tinyInteger('status')->comment('状态  0 利润记录  1 充值记录');
            $table->tinyInteger('type')->default(0)->comment('金额状态  1 增加 2 减少');
            $table->tinyInteger('is_try')->default(1)->comment('是否试看码 1 否 2 是');
            $table->integer('number')->default(0)->comment('数量(针对授权码的)');
            $table->integer('own_id')->default(0)->comment('事件中用户的id');
            $table->integer('create_id')->default(0)->comment('创建金额用户id');
            $table->integer('assort_id')->default(0)->comment('授权码id');
            $table->string('user_account')->default('')->comment('生成授权码用户账号');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_huobis');
    }
};
