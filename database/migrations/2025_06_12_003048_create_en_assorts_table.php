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
        Schema::create('en_assorts', function (Blueprint $table) {
            $table->comment('配套管理');
            $table->increments('id');
            $table->string('assort_name', 128)->default('')->comment('配套名称');
            $table->integer('duration')->comment('时长');
            $table->integer('try_num')->default(0)->comment('试看码数量');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_en_assorts');
    }
};
