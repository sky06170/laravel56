<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrencyRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currency_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('currency_category_id')->unsigned();
            $table->string('bank', 8);
            $table->string('immediate_buy',8)->nullable();
            $table->string('immediate_sell',8)->nullable();
            $table->string('cash_buy',8)->nullable();
            $table->string('cash_sell',8)->nullable();
            $table->foreign('currency_category_id')
                ->references('id')->on('currency_categorys')->onDelete('cascade');
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
        Schema::dropIfExists('currency_records');
    }
}
