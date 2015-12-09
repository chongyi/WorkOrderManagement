<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->comment('消息发布者 ID，当该值为 0 表示系统发布的消息');
            $table->integer('target_id')->unsigned()->comment('消息目标用户 ID');
            $table->boolean('system_message')->default(false)->comment('表示当前消息是否为系统消息');
            $table->string('title')->comment('消息标题');
            $table->text('content')->comment('消息内容');
            $table->boolean('read')->default(false)->comment('是否已读');
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
        Schema::drop('messages');
    }
}
