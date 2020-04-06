<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMerchantCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('merchant_id')->nullable(false)->comment('商家id');
            $table->string('category')->nullable(false)->comment('商家分类');
            $table->tinyInteger('is_handle')->nullable(false)->default(0)->comment('是否人为设置');
            $table->index(['category'], 'idx_category');
            $table->index(['merchant_id'], 'idx_merchantid');
            $table->softDeletes();
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
        Schema::dropIfExists('merchant_categories');
    }
}
