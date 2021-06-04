<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileSystemFieldToFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files_managed', function (Blueprint $table) {
            $table->string('filesystem')->default('public');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('files_managed', 'filesystem')) {
            Schema::table('files_managed', function (Blueprint $table) {
                $table->dropColumn('filesystem');
            });
        }
    }
}
