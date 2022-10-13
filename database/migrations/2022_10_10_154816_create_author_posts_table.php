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
        Schema::create('authors_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('author')->nullable();
            $table->unsignedBigInteger('post');

            $table->foreign('author')->references('id')->on('authors')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('post')->references('id')->on('posts')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('author_posts');
    }
};
