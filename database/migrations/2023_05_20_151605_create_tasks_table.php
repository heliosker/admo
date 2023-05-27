<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255)->default('');
            $table->string('adv_id');
            $table->integer('peak_price')->default(0);
            $table->float('min_roi')->default(0);
            $table->boolean('is_allow_bulk')->default(false);
            // $table->boolean('is_allow_unbind');
            $table->string('punish', 128)->default('');
            $table->string('marketing_goal', 255)->default('');
            $table->string('status', 60)->default('');
            $table->timestamp('scanned_at', 0)->nullable()->comment('扫描时间');
            $table->timestamps();
            $table->softDeletes();

            $table->index('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('tasks');
    }
}
