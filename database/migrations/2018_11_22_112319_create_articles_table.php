<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
// php artisan make:migration create_articles_table
        // php artisan migrate
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('article_id')->unsigned();
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->text('body');
            $table->integer('visibility')->unsigned()->default(0);
            $table->integer('user_id')->unsigned()->default(0);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('views')->unsigned()->default(0);

            $table->datetime('publish_on');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('articles');
    }
}
