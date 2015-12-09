<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrderMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_order_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('work_order_id')->unsigned()->comment('对应的工单 ID')->index();
            $table->integer('reply_id')->unsigned()->default(0)->comment('回应的对象 ID，为 0 表示针对整个工单的消息');
            $table->integer('user_id')->unsigned()->comment('响应人（用户） ID')->index();
            $table->mediumText('content')->comment('响应的消息内容');
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
        Schema::drop('work_order_messages');
    }
}
