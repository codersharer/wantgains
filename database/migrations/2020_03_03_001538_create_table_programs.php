<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

//program即在联盟后台申请合作的优惠商家
class CreateTablePrograms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('affiliate_id')->nullable(false)->comment('联盟平台id');
            $table->string('name')->nullable()->comment('program名称');
            $table->text('category')->nullable()->comment('商家分类');
            $table->text('country')->nullable()->comment('商家所属国家');
            $table->string('seven_day_epc')->nullable()->comment('7天每100次点击平均赚取佣金');
            $table->string('three_month_epc')->nullable()->comment('3个月每100次点击平均赚取佣金');
            $table->string('advertise_type')->nullable()->comment('广告类型 cpc|cpa|cps等');
            $table->string('homepage')->nullable()->comment('商家网址');
            $table->string('domain')->nullable()->comment('商家domain');
            $table->bigInteger('domain_id')->nullable()->comment('domain_id');
            $table->string('merchant_name')->nullable()->comment('商家名称');
            $table->string('merchant_id')->nullable()->comment('商家id');
            $table->text('description')->nullable()->comment('商家描述');
            $table->string('id_in_aff')->nullable()->comment('商家在平台的id');
            $table->string('commission_rate')->nullable()->comment('佣金比例');
            $table->tinyInteger('status_in_aff')->nullable(false)->default(0)->comment('program在平台的状态 0:不在线 1:在线');
            $table->tinyInteger('status_in_dashboard')->nullable(false)->default(0)->comment('program在我们后台的状态');
            $table->tinyInteger('support_deep')->default(0)->comment('是否支持深度跳转 0：不支持 1：支持');
            $table->text('default_track_link')->nullable()->comment('联盟实际返回地址');
            $table->text('real_track_link')->nullable()->comment('可用于替换地址');
            $table->index(['affiliate_id','domain'], 'idx_affiliateid_domain');
            $table->index(['status_in_dashboard'],'idx_status_in_dashboard');
            $table->index(['status_in_aff'],'idx_status_in_aff');
            $table->unique(['affiliate_id','id_in_aff'],'unique_affiliateid_idinaff');
            $table->index(['domain_id','affiliate_id'],'idx_domainid_affiliateid');
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
        Schema::dropIfExists('programs');
    }
}
