<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlarmLogsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alarm_logs', function (Blueprint $table) {
            $table->id('id');
            $table->integer('task_id')->default(0)->comment('任务ID');
            $table->string('task_name')->default(0)->comment('任务名称');
            $table->bigInteger('adv_id')->default(0)->comment('广告主ID');
            $table->integer('is_valid')->default(-1)->comment('授权状态');
            $table->integer('ad_id')->default(0)->comment('广告ID');
            $table->string('ad_name')->default('')->comment('广告名称');
            $table->string('punish_rule')->default('')->comment('处罚规则');
            $table->boolean('exec_result')->default(false)->comment('执行结果');
            $table->tinyInteger('type')->default(1)->comment('日志类型');
            $table->string('cause', 255)->default('')->comment('原因');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('alarm_logs');
    }
}
