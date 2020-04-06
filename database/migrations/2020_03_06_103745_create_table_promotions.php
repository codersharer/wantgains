<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//促销链接、促销商品表
class CreateTableLinkFeeds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('affiliate_id')->nullable(false)->comment('联盟id');
            $table->bigInteger('domain_id')->nullable(false)->comment('domainid');
            $table->string('domain')->nullable()->comment('domain');
            $table->string('name')->nullable(false)->comment('促销名称');
            $table->string('keyword')->nullable()->comment('促销关键词，某些特定的deals或者coupon code只能使用特定的商品，关键词方便关联使用');
            $table->tinyInteger('type')->nullable(false)->default(1)->comment('促销类型 1.deals 2.coupon');
            $table->text('description')->nullable()->comment('描述');
            $table->tinyInteger('coupon_code')->nullable()->comment('coupon code，如果是coupon类型促销会存在coupon code');
            $table->string('discount')->nullable()->comment('具体折扣率');
            $table->float('price',10,.2)->nullable()->comment('具体价格');
            $table->string('url')->nullable()->comment('促销链接');
            $table->tinyInteger('scenes')->nullable(false)->default(1)->comment('应用场景');
            $table->dateTime('promotion_start_at')->nullable()->comment('促销开始时间');
            $table->dateTime('promotion_end_at')->nullable()->comment('促销结束时间');
            $table->tinyInteger('status')->nullable(false)->default(1)->comment('状态 0.下线 1.在线');
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
        Schema::dropIfExists('linkfeeds');
    }
}
