<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//联盟平台表
class CreateTableAffs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->string('name')->nullable(false)->comment('联盟名');
            $table->string('homepage')->nullable(false)->comment('联盟网址');
            $table->string('login_page')->nullable(false)->comment('联盟登录地址');
            $table->string('domain')->nullable(false)->comment('联盟domain');
            $table->string('account')->nullable()->comment('账号');
            $table->string('password')->nullable()->comment('密码');
            $table->integer('weight')->nullable(false)->default(1)->comment('联盟权重，之后会和选择哪个program有关联');
            $table->string('creator')->nullable(false)->comment('创建用户');
            $table->string('status')->nullable(1)->comment('状态 0.停用 1.启用');
            $table->dateTime('register_at')->nullable()->comment('注册时间');
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
        Schema::dropIfExists('affiliates');
    }
}
