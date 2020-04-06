<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_id_in_aff')->nullable(false)->comment('link在联盟中的id');
            $table->integer('affiliate_id')->nullable(false)->comment('联盟id');
            $table->string('id_in_aff')->nullable(false)->comment('商家在联盟中id');
            $table->bigInteger('domain_id')->nullable(false)->comment('domainid');
            $table->string('domain')->nullable()->comment('domain');
            $table->text('name')->nullable(false)->comment('促销名称');
            $table->text('category')->nullable()->comment('分类');
            $table->text('description')->nullable()->comment('描述');
            $table->text('track_link')->nullable()->comment('促销链接');
            $table->text('destination_url')->nullable()->comment('实际最终地址');
            $table->text('image_url')->nullable()->comment('图片url');
            $table->float('price',20,2)->nullable()->comment('原价');
            $table->float('real_price',20,2)->nullable()->comment('实际价格');
            $table->string('sku')->nullable()->comment('sku');
            $table->dateTime('promotion_start_at')->nullable()->comment('促销开始时间');
            $table->dateTime('promotion_end_at')->nullable()->comment('促销结束时间');
            $table->tinyInteger('is_promotion')->nullable(false)->default(0)->comment('是否为促销 0.否 1.是');
            $table->tinyInteger('status')->nullable(false)->default(1)->comment('状态 0.下线 1.在线');
            $table->unique(['affiliate_id', 'product_id_in_aff'], 'unique_affiliateid_productidinaff');
            $table->index(['domain'], 'idx_domain');
            $table->index(['domain_id'], 'idx_domainid');
            $table->index(['updated_at'], 'idx_updatedat');
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
        Schema::dropIfExists('products');
    }
}
