<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrderHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order_histories', function (Blueprint $table) {
            $table->increments('id')->comment('工单历史事件 ID');
            $table->integer('work_order_id')->unsigned()->comment('工单 ID')->index();
            $table->integer('user_id')->unsigned()->comment('历史事件产生人')->index();
            $table->integer('event_id')->unsigned()->comment('事件 ID');
            $table->string('remark')->comment('事件备注');
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
        Schema::drop('work_order_histories');
    }
}
