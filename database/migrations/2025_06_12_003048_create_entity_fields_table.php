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
        Schema::create('entity_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 64)->default('');
            $table->string('type', 191)->default('');
            $table->string('comment', 100)->default('');
            $table->string('default_value', 191)->default('');
            $table->string('form_name', 20)->default('');
            $table->string('form_type', 191)->default('');
            $table->string('form_comment', 100)->default('');
            $table->string('form_params', 1024)->default('');
            $table->unsignedTinyInteger('is_show')->default(1);
            $table->unsignedTinyInteger('is_show_inline')->default(0);
            $table->unsignedTinyInteger('is_edit')->default(1);
            $table->unsignedTinyInteger('is_required')->default(0);
            $table->unsignedInteger('entity_id')->default(0);
            $table->unsignedInteger('order')->default(77);
            $table->timestamps();
            $table->string('form_default_value', 191)->default('')->comment('字段表单默认值');

            $table->unique(['entity_id', 'name'], 'entity_fields_entity_id_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hot_entity_fields');
    }
};
