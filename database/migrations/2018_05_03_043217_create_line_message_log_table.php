<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLineMessageLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('line_message_log', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message')->nullable();
            $table->string('message_type',255)->default('msg');
            $table->string('spokesman',255)->default('bot');
            $table->string('replyToken',255)->nullable();
            $table->string('uid',255)->nullable();
            $table->string('gid',255)->nullable();
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
        Schema::dropIfExists('line_message_log');
    }
}
