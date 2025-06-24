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
        Schema::create('try_codes', function (Blueprint $table) {
            $table->comment('试看码管理');
            $table->increments('id');
            $table->integer('user_id')->comment('用户id（创建人id）');
            $table->string('number', 128)->default('')->comment('获取数量');
            $table->string('description')->default('')->comment('获取原因');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_try_codes');
    }
};
