<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCrawleLogs extends Migration
{
    /**
     * //抓取记录
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crawle_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id')->nullable(false)->comment('联盟ID');
            $table->string('type')->nullable(false)->default('programs')->comment('抓取类型');
            $table->integer('current_page')->nullable(false)->default(1)->comment('当前正在抓取的页码');
            $table->bigInteger('current_program_id')->nullable()->comment('如果是product或者linkfeed抓取需要知道具体的programid来续抓');
            $table->tinyInteger('is_finish')->nullable(false)->default(0)->comment('是否完成');
            $table->dateTime('started_at')->nullable(false)->comment('起始时间');
            $table->dateTime('finished_at')->nullable()->comment('结束时间');
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
        Schema::dropIfExists('crawle_logs');
    }
}
