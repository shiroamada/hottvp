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
        Schema::create('hot_articles', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title', 191)->comment('标题');
            $table->text('content')->comment('正文');
            $table->unsignedInteger('admin_user_id')->default(0)->comment('作者');
            $table->unsignedInteger('category_id')->comment('分类');
            $table->string('introduction', 500)->comment('简介');
            $table->string('keyword')->default('')->comment('关键字');
            $table->string('cover_image')->default('')->comment('封面图');
            $table->unsignedTinyInteger('is_publish')->comment('是否已发布');
            $table->string('recommend')->default('');
            $table->string('title_color')->default('');
            $table->text('toutiao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_articles');
    }
};
