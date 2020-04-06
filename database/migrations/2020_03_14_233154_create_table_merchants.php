<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMerchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable(false)->comment('商家名');
            $table->string('slug')->nullable(false)->comment('url标识');
            $table->string('domain')->nullable(false)->comment('domain');
            $table->bigInteger('domain_id')->nullable(false)->comment('domain_id');
            $table->text('description')->nullable()->comment('商家描述');
            $table->tinyInteger('is_handle')->nullable(false)->default(0)->comment('是否手动编辑过，如果是则以后都不会更新');
            $table->tinyInteger('is_subscribe')->nullable(false)->default(0)->comment('是否已订阅');
            $table->string('subscribe_username')->nullable()->comment('订阅用户名');
            $table->string('subscribe_password')->nullable()->comment('订阅用户密码');
            $table->text('logo')->nullable()->comment('logo');
            $table->index(['slug'], 'idx_slug');
            $table->unique(['domain_id'], 'unique_domainid_slug');
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
        Schema::dropIfExists('merchants');
    }
}
