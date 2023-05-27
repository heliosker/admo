<?php

namespace App\Console\Commands;

use App\Models\Ads;
use App\Models\AlarmLogs;
use App\Models\Shops;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Models\Task as TaskModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Task extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '任务巡查，AD是否违规';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tasks = TaskModel::inprogress()->get();

        if ($tasks->isNotEmpty()) {
            foreach ($tasks as $task) {
                if (!empty($task->adv_id)) {
                    $ads = Ads::whereIn('adv_id', $task->adv_id)->where('marketing_goal', $task->marketing_goal)->where('status', Ads::STATUS_DELIVERY_OK)->get();
                    // 判定计划是否违规
                    if ($ads->isNotEmpty()) {
                        foreach ($ads as $ad) {
                            // 是否允许放量
                            if ($task->is_allow_bulk == TaskModel::NOT_ALLOW_BULK) {
                                if ($ad->auto_extend_enabled != 0) {
                                    // 放量，处罚
                                    $ret = $this->punishment($ad, $task->punish);
                                    $cause = '启用了智能放量';
                                    $this->log($task->id, $task->name, $ad->adv_id, -1, $ad->id, $ad->ad_name, $task->punish, $ret, $cause, AlarmLogs::TYPE_TASK);
                                }
                            }
                            // 判断 ROI
                            if ($ad->deep_external_action == 'AD_CONVERT_TYPE_LIVE_PAY_ROI' && $ad->deep_bid_type == 'MIN') {
                                if ($ad->roi_goal < $task->min_roi) {
                                    // ROI 过低，处罚
                                    $ret = $this->punishment($ad, $task->punish);
                                    $cause = 'ROI 低于任务设定值：' . $task->min_roi;
                                    $this->log($task->id, $task->name, $ad->adv_id, -1, $ad->id, $ad->ad_name, $task->punish, $ret, $cause, AlarmLogs::TYPE_TASK);
                                }
                            }
                            if (($ad->cpa_bid > 0) && ($ad->cpa_bid > $task->peak_price)) {
                                // 出价过高，处罚
                                $ret = $this->punishment($ad, $task->punish);
                                $cause = '出价高于任务设定值：' . $task->peak_price;
                                $this->log($task->id, $task->name, $ad->adv_id, -1, $ad->id, $ad->ad_name, $task->punish, $ret, $cause, AlarmLogs::TYPE_TASK);
                            }
                        }
                    }
                    
                    // 更新任务巡查时间
                    $task->scanned_at = Carbon::now();
                    $task->save();
                }
            }
        }
    }

    public function log($taskId, $taskName, $advId, $isValid, $adId, $adName, $punishRule, $execResult, $cause, $type)
    {
        $log = (new AlarmLogs());
        $log->task_id = $taskId;
        $log->task_name = $taskName;
        $log->adv_id = $advId;
        $log->is_valid = $isValid;
        $log->ad_id = $adId;
        $log->ad_name = $adName;
        $log->punish_rule = $punishRule;
        $log->exec_result = $execResult;
        $log->cause = $cause;
        $log->type = $type;
        return $log->save();
    }

    public function punishment(Ads $ad, string $rule): bool
    {
        $opt = ($rule == TaskModel::PUNISH_PAUSE) ? 'DISABLE' : 'DELETE';
        $store = Shops::where(['advertiser_id' => $ad->adv_id])->first();
        $rsp = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Access-Token' => $store->getShopAccessToken(),
        ])->post('https://ad.oceanengine.com/open_api/v1.0/qianchuan/ad/status/update/', [
            'ad_ids' => [$ad->ad_id],
            'advertiser_id' => $ad->adv_id,
            'opt_status' => $opt,
        ]);

        if ($rsp->json('code') != 0) {
            Log::error('AD ID:' . $ad->ad_id . '更新计划[' . $opt . ']操作失败! 原因：' . $rsp->json('message'));
            $this->error('AD ID:' . $ad->ad_id . '更新计划[' . $opt . ']操作失败! 原因：' . $rsp->json('message'));
            return false;
        }
        $this->info('AD ID:' . $ad->ad_id . '更新计划[' . $opt . ']操作成功!');
        return true;
    }
}
