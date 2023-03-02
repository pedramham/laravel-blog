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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();
            $table->string('name')->nullable();;
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('status')->default('draft');
            $table->text('subject');
            $table->text('description');
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->text('meta_language')->nullable();
            $table->text('tweet_text')->nullable();
            $table->string('category_type')->nullable();
            $table->integer('menu_order')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('menu_status')->default(0);
            $table->boolean('visible_index_status')->default(0);
            $table->string('pic_small');
            $table->string('pic_large');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
