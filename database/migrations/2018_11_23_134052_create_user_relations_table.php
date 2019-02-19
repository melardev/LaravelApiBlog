<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_relations', function (Blueprint $table) {
            $table->unsignedInteger('follower_id');
            $table->unsignedInteger('following_id');
            $table->timestamps();

            $table->primary(['follower_id', 'following_id']);

            $table->foreign('follower_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('following_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_relations');
    }
}
