<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject')->comment('工单主题、标题')->index();
            $table->integer('group_id')->unsigned()->comment('工单所属的工作组，0 表示全局')->index();
            $table->integer('user_id')->unsigned()->comment('创建者 ID')->index();
            $table->integer('category_id')->unsigned()->comment('分类 ID');
            $table->integer('activity')->unsigned()->default(0)->comment('活动次数，每次工单的回复都会增加该值');
            $table->tinyInteger('sort')->unsigned()->comment('排序、等级、重要程度');
            $table->tinyInteger('status')->unsigned()->comment('工单状态 0 已关闭 1 待受理 2 受理中 3 已解决')->default(1);
            $table->boolean('display')->default(true)->comment('工单可视状态');

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
        Schema::drop('work_orders');
    }
}
