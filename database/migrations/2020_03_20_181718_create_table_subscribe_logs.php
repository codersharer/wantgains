<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSubscribeLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('mail')->nullable(false)->comment('邮箱');
            $table->tinyInteger('is_send')->nullable(false)->default(1)->comment('是否发送成功');
            $table->dateTime('send_at')->nullable(false)->comment('发送时间');
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
        Schema::dropIfExists('subscribe_logs');
    }
}
