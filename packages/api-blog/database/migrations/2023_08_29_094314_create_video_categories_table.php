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
        Schema::create('video_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('slug')->unique();
            $table->string('status')->default('draft')->nullable();
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_language')->nullable();
            $table->text('tweet_text')->nullable();
            $table->integer('menu_order')->nullable();
            $table->integer('priority')->default('0')->nullable();
            $table->boolean('menu_status')->default('0')->nullable();
            $table->boolean('visible_index_status')->default('0')->nullable();
            $table->string('pic_small')->nullable();
            $table->string('pic_large')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_categories');
    }
};
