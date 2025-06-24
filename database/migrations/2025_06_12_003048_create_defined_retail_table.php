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
        Schema::create('defined_retail', function (Blueprint $table) {
            $table->comment('自定义零售价');
            $table->increments('id');
            $table->integer('user_id')->comment('用户id');
            $table->integer('assort_id')->comment('配套id');
            $table->decimal('money', 9)->default(0)->comment('零售价');
            $table->timestamp('created_at')->nullable()->comment('创建时间');
            $table->timestamp('updated_at')->nullable()->comment('更新时间');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_defined_retail');
    }
};
