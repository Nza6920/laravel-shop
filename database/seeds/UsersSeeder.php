<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // 生成 100 个用户
        factory(\App\Models\User::class, 100)->create();
    }
}
