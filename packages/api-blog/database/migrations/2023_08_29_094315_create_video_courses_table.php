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


        Schema::create('video_courses', function (Blueprint $table) {

            $table->increments('id');
            $table->softDeletes();
            $table->string('name');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->decimal('price', 8, 2)->nullable();
            $table->decimal('discount', 8, 2)->nullable();
            $table->boolean('is_free')->default(0)->nullable();
            $table->string('sell_count')->nullable();
            $table->integer('priority')->default(0)->nullable();
            $table->string('status')->default('draft');
            $table->boolean('visible_index_status')->default(0)->nullable();
            $table->boolean('menu_status')->default(0)->nullable();
            $table->string('pic_small')->nullable();
            $table->string('pic_large')->nullable();
            $table->timestamps();
            $table->integer('video_category_id')->unsigned();
            $table->foreign('video_category_id')->references('id')->on('video_categories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_courses');
    }
};
