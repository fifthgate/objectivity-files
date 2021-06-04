<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilesTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files_managed', function (Blueprint $table) {
            $table->bigIncrements('fid');
            $table->string('title', 255);
            $table->string('url', 255)->unique();
            $table->string('filetype', 255);
            $table->timestamps();
            $table->bigInteger('author_uid')->unsigned();
            $table->integer('is_permanent')->default(0);
            $table->bigInteger('last_editor_uid')->unsigned();
            $table->foreign('author_uid')->references('id')->on('users');
            $table->foreign('last_editor_uid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('content_files');
        Schema::dropIfExists('files_managed');
    }
}
