<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserTracks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_tracks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('source')->nullable(false)->comment('来源');
            $table->text('value')->nullable(false)->comment('内容');
            $table->index(['source'], 'idx_source');
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
        Schema::dropIfExists('user_tracks');
    }
}
