<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Str;
use Cache;

class EmailVerificationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    // 只需要邮件通知
    public function via($notifiable)
    {
        return ['mail'];
    }

    // 发送邮件会调用此方法来构建邮件内容, 参数是 App\Models\User 对象
    public function toMail($notifiable)
    {
        // 用 Str 类内置的函数随机生成字符串, 参数是要生成字符串的长度
        $token  = Str::random(16);
        // 往缓存中写入这个随机字符串, 有效时间为三十分钟
        Cache::set('email_verification_'.$notifiable->email, $token, 30);

        $url = route('email_verification.verify', ['email' => $notifiable->email, 'token'=>$token]);

        return (new MailMessage)
                    ->greeting($notifiable->name . '您好: ')
                    ->subject('注册成功,请验证您的邮箱')
                    ->line('请点击下方链接验证您的邮箱')
                    ->action('验证', $url);
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
