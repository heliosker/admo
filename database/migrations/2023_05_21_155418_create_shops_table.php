<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id('id');
            $table->integer('parent_id')->default(0);
            $table->unsignedBigInteger('advertiser_id')->default(0);
            $table->string('advertiser_name', 255)->default('');
            $table->string('company', 255)->default('');
            $table->string('first_name', 255)->default('');
            $table->string('second_name', 255)->default('');
            $table->tinyInteger('is_valid')->default(-1);
            $table->string('account_role', 255)->default('');
            $table->string('access_token', 255)->default('');
            $table->unsignedInteger('access_token_expires_at')->default(0);
            $table->string('refresh_token', 255)->default('');
            $table->unsignedInteger('refresh_token_expires_at')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('advertiser_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('shops');
    }
}
