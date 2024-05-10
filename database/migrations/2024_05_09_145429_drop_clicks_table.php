<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('clicks');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       //syncing the schema
    }
}
