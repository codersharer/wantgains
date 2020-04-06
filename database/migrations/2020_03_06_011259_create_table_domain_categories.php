<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//商家分类
class CreateTableDomainCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('domain_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain')->nullable(false)->comment('domain其实就是商家domain');
            $table->bigInteger('domain_id')->nullable(false)->comment('domain_id');
            $table->string('category')->nullable(false)->comment('分类');
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
        Schema::dropIfExists('domain_categories');
    }
}
