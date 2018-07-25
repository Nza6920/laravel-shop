<?php

namespace App\Http\Middleware;

use Closure;

class CheckIfEmailVerified
{
    public function handle($request, Closure $next)
    {
        if (!$request->user()->email_verified) {
          // 如果是 AJAX 请求则通过 json 返回
            if ($request->expectsJson()) {
                return response()->json(['msg' => '请先验证邮箱'], 400);
            }
            return redirect(route('email_verify_notice'));
        }
        return $next($request);
    }
}
