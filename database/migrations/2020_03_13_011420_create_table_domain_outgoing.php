<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 出站选择表
 *
 * Class CreateTableDomainOutgoing
 */
class CreateTableDomainOutgoing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('domain_id')->nullable(false)->comment('domain_id');
            $table->string('domain')->nullable(false)->comment('domain');
            $table->bigInteger('affiliate_id')->nullable(false)->comment('联盟id');
            $table->string('affiliate_name')->nullable(false)->comment('联盟名');
            $table->string('program_id')->nullable(false)->comment('program_id');
            $table->text('track_link')->nullable(false)->comment('实际出站链接');
            $table->tinyInteger('is_handle')->nullable(false)->default(0)->comment('是否为手动操作');
            $table->string('op_name')->nullable(false)->default('system')->comment('操作人');
            $table->unique(['domain_id'], 'unique_domainid');
            $table->index(['affiliate_id', 'domain_id'], 'idx_affiliateid_domainid');
            $table->index(['program_id'], 'idx_programid');
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
        Schema::dropIfExists('domain_outgoing');
    }
}
