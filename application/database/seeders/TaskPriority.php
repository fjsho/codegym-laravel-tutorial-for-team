<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class TaskPriority extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {   //Model名は仮入れ
        \App\Models\TaskPriority::insert([
            ['name' => '大', 'display_order' => 1],
            ['name' => '中', 'display_order' => 2],
            ['name' => '小', 'display_order' => 3],
        ]);
    }
}
