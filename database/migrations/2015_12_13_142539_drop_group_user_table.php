<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropGroupUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('group_user');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('group_user', function (Blueprint $blueprint) {
            $blueprint->integer('user_id')->unsigned()->comment('账户 ID');
            $blueprint->integer('group_id')->unsigned()->comment('工作组 ID');

            $blueprint->primary(['user_id', 'group_id']);

            $blueprint->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $blueprint->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }
}
