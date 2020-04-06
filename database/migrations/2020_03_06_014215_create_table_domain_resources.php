<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//商家资源表，如logo， 图片等
class CreateTableDomainResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_resources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('domain_id')->nullable(false)->comment('商家domain');
            $table->tinyInteger('type')->nullable(1)->comment('资源类型 1.logo 之后在model添加常量');
            $table->text('path')->nullable(false)->comment('资源路径');
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
        Schema::dropIfExists('domain_resources');
    }
}
