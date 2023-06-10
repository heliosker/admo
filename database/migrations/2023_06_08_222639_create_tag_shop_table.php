<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops_tags', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tags_id');
            $table->unsignedBigInteger('shops_id');
            $table->unique(['tags_id', 'shops_id']);

            $table->foreign('tags_id')->references('id')->on('tags')->onDelete('cascade');
            $table->foreign('shops_id')->references('id')->on('shops')->onDelete('cascade');

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
        Schema::dropIfExists('shops_tags');
    }
}
