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
        Schema::create('videos', function (Blueprint $table) {
            // Add an auto-incrementing primary key
            $table->increments('id');
            $table->softDeletes();
            $table->string('name');
            $table->string('title');
            $table->string('status')->default('draft');
            $table->string('slug')->unique();
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('duration')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('video_url')->nullable();
            $table->string('video_file')->nullable();
            $table->string('video_file_type')->nullable();
            $table->string('video_number')->nullable();
            $table->integer('priority')->default(0)->nullable();
            $table->timestamps();

            $table->integer('video_course_id')->unsigned()->nullable();
            $table->foreign('video_course_id')->references('id')->on('video_courses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
