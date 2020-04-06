<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 出站记录表
 *
 * Class CreateTableOutgoingTracking
 */
class CreateTableOutgoingTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outgoing_tracking', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('domain_id')->nullable(false)->comment('domainid');
            $table->bigInteger('program_id')->nullable(false)->comment('programid');
            $table->bigInteger('affiliate_id')->nullable(false)->comment('affiliateid');
            $table->text('track_link')->nullable(false)->comment('track_link');
            $table->string('ip')->nullable(false)->comment('ip');
            $table->text('user_agent')->nullable(false)->comment('user_agent');
            $table->text('country')->nullable(false)->comment('country');
            $table->string('sid')->nullable()->comment('sid');
            $table->index(['affiliate_id', 'domain_id'], 'idx_affiliateid_domainid');
            $table->index(['created_at'], 'idx_createdat');
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
        Schema::dropIfExists('outgoing_tracking');
    }
}
