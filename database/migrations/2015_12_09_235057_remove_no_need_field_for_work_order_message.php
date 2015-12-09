<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNoNeedFieldForWorkOrderMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_order_messages', function(Blueprint $blueprint) {
            $blueprint->dropColumn('reply_id'); // 没必要
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_order_messages', function(Blueprint $blueprint) {
            $blueprint->integer('reply_id')->unsigned()->default(0)->comment('回应的对象 ID，为 0 表示针对整个工单的消息');
        });
    }
}
