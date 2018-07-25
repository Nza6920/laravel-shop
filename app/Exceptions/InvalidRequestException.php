<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

// 用户错误行为触发异常
class InvalidRequestException extends Exception
{
    public function __construct($message = "", $code = 400)
    {
        parent::__construct((string)$message, (int)$code);
    }

    public function render(Request $request)
    {
        if($request->expectsJson()) {
          // json() 方法的第二个参数就是 HTTP 返回码
          return response()->json(['msg' => $this->message], $this->code);
        }

        return view('pages.error', ['msg' => $this->message]);
    }
}
