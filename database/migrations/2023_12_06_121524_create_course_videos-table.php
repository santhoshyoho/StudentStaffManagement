<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id('video_id');
            $table->unsignedBigInteger('course_id');
            $table->string('video_title');
            $table->string('video_url');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('course_id')->references('course_id')->on('courses')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_videos');
    }
};
