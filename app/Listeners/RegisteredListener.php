<?php

namespace App\Listeners;

use App\Notifications\EmailVerificationNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RegisteredListener implements ShouldQueue
{
    // 当事件被触发时, 对应事件的监听器的 handle() 方法会被调用
    public function handle($event)
    {
        // 获取到刚刚注册的用户
        $user = $event->user;
        // 调用 notify() 发送通知
        $user->notify(new EmailVerificationNotification());
    }
}
