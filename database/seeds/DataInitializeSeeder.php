<?php

use Illuminate\Database\Seeder;

class DataInitializeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tables = [
            'categories',
            'groups',
            'users',
            'involvements',
            'messages',
            'password_resets',
            'work_order_histories',
            'work_order_messages',
            'work_orders'
        ];

        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
    }
}
