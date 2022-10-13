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
            $table->id();
            $table->string('title', 100);
            $table->unsignedBigInteger('media')->nullable();
            $table->unsignedBigInteger('publicationName')->nullable();
            $table->text('summary');
            $table->float('positive');
            $table->float('neutral');
            $table->float('negative');

            $table->foreign('media')->references('id')->on('media')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('publicationName')->references('id')->on('publications')->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('posts');
    }
};
