<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexOnLinksCreator extends Migration
{
    /**
     * Run the migrations
     */
    public function up()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->index('creator');
        });
    }

    /**
     * Reverse the migrations
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropIndex('links_creator_index');
        });
    }
}
