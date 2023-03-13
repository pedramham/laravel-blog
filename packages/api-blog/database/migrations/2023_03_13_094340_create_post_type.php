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
        Schema::create('post_type', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->integer('post_id')->nullable()->unsigned()->index();
            $table->integer('video_id')->nullable()->unsigned()->index();
            $table->timestamps();

            // Set
            $table->foreign('post_id')
                ->references('id')
                ->on('posts')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Set FK tagspivot --- videos
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_type');
    }
};
