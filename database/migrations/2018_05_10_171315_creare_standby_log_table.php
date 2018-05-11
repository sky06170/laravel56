<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreareStandbyLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standby_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid', 255)->nullable();
            $table->string('log', 255)->nullable();
            $table->string('status', 255)->default('null');
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
        Schema::dropIfExists('standby_log');
    }
}
