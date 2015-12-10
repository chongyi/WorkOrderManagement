<?php

use Illuminate\Database\Seeder;

class CategoryDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\WorkOrderManagement\Work\Category::truncate();

        $categories = [
            [
                'display_name' => '新增功能',
                'description'  => '新增加了一些项目功能'
            ],
            [
                'display_name' => '修改功能',
                'description'  => '需求变更，需要修改模块功能'
            ],
            [
                'display_name' => 'BUG 报告',
                'description'  => '发现测试中遇到一些执行异常，需要解决'
            ]
        ];

        foreach ($categories as $category) {
            \App\WorkOrderManagement\Work\Category::create($category);
        }
    }
}
