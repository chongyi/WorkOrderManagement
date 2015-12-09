<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('display_name')->comment('工作组名称')->index();
            $table->text('description')->comment('工作组描述');
            $table->boolean('status')->default(true)->comment('工作组可访问状态');
            $table->integer('user_id')->unsigned()->comment('创建者 ID')->index();
            $table->timestamps();
        });

        Schema::create('group_user', function (Blueprint $blueprint) {
            $blueprint->integer('user_id')->unsigned()->comment('账户 ID');
            $blueprint->integer('group_id')->unsigned()->comment('工作组 ID');

            $blueprint->primary(['user_id', 'group_id']);

            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $blueprint->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('group_user');

        Schema::drop('groups');
    }
}
