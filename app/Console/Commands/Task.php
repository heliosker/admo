<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task as TaskModel;

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
        TaskModel::inprogress()->get();
    }
}
