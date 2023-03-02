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
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->string('name');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subject')->nullable();
            $table->text('description')->nullable();
            $table->string('pic_small')->nullable();
            $table->string('pic_large')->nullable();
            $table->timestamps();
            // Set FK posts --- categories
            $table->integer('category_id')->unsigned()->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
