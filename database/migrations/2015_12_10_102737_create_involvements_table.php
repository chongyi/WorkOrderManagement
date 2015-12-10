<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvolvementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('involvements', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->comment('用户 ID');
            $table->integer('involvement_id')->unsigned()->comment('参与对象的 ID');
            $table->string('involvement_type')->comment('参与对象的模型、类型');

            $table->primary(['user_id', 'involvement_id', 'involvement_type']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('involvements');
    }
}
