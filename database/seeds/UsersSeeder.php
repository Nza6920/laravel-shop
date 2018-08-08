<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // 生成 100 个用户
        factory(\App\Models\User::class, 100)->create();

        $user = \App\Models\User::first();
        $user->name = 'pepsi';
        $user->email = 'niu@qq.com';
        $user->password = bcrypt('qwerty');
        $user->save();
    }
}
