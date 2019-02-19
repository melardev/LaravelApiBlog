<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();

            $table->timestamps();
        });

        Schema::create('categorizables', function (Blueprint $table) {
            $table->morphs('categorizable');

            $table->integer('category_id')->unsigned();
            $table->foreign('category_id')->references('id')->on('categories');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('categorizables');
        Schema::dropIfExists('categories');
    }
}
