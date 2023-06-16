<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id('id');
            $table->string('ad_id')->comment('AD id');
            $table->string('adv_id')->comment('广告主');
            $table->string('ad_create_time', 128)->default('')->comment('计划创建时间');
            $table->string('ad_modify_time', 128)->default('')->comment('计划更新时间');
            $table->string('lab_ad_type', 255)->default('')->comment('推广方式，NOT_LAB_AD：非托管计划，LAB_AD：托管计划');
            $table->string('marketing_goal', 128)->default('')->comment('按营销目标过滤，允许值：VIDEO_PROM_GOODS：推商品；LIVE_PROM_GOODS：推直播间');
            // $table->string('marketing_scene',128)->default('')->comment('广告类型，FEED 通投广告，SEARCH 搜索广告');
            $table->string('name', 128)->default('')->comment('计划名称');
            $table->string('status', 128)->default('')->comment('计划投放状态');
            $table->string('opt_status', 128)->default('')->comment('划操作状态');
            $table->integer('aweme_id')->default(0)->comment('抖音ID');
            $table->string('aweme_name', 128)->default('')->comment('抖音昵称');
            $table->string('aweme_show_id', 128)->default('')->comment('抖音号ID，抖音客户端展示ID');
            $table->string('aweme_avatar', 128)->default('')->comment('抖音头像');
            $table->string('deep_external_action', 128)->default('')->comment('深度转化目标');
            $table->string('deep_bid_type', 128)->default('')->comment('深度出价方式');
            $table->double('roi_goal')->default(0)->comment('支付ROI目标');
            $table->double('cpa_bid')->default(0)->comment('转化出价');
            $table->string('start_time', 128)->default('')->comment('投放开始时间');
            $table->string('end_time', 128)->default('')->comment('投放结束时间');
            $table->integer('auto_extend_enabled')->default(0)->comment('是否启用智能放量');

            $table->double('stat_cost')->default(0)->comment('消耗');
            $table->double('convert_cost')->default(0)->comment('转化成本');
            $table->double('pay_order_amount')->default(0)->comment('直接成交金额');
            $table->integer('pay_order_count')->default(0)->comment('直接成交单数');
            $table->double('prepay_and_pay_order_roi')->default(0)->comment('直接支付roi');
            // $table->double('create_order_roi')->default(0)->comment('直接下单roi');

            //$table->integer('convert_cnt')->default(0)->comment('转化数');
            //$table->double('convert_rate')->default(0)->comment('转化率');


            $table->timestamps();
            $table->softDeletes();

            $table->index('ad_id');
            $table->index('adv_id');
            $table->index('name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ads');
    }
}
