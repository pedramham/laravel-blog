<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //Many to Many relationship between posts and tags
        Schema::create('video_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->integer('video_id')->nullable()->unsigned()->index();
            $table->integer('tag_id')->nullable()->unsigned()->index();
            $table->timestamps();

            // Set
            $table->foreign('video_id')
                ->references('id')
                ->on('videos')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Set FK tagspivot --- tags
            $table->foreign('tag_id')
                ->references('id')
                ->on('tags')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('video_tag');

    }
};
