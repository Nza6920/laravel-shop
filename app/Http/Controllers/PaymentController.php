<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Events\OrderPaid;
use App\Exceptions\InvalidRequestException;

class PaymentController extends Controller
{
    // 支付宝支付
    public function payByAlipay(Order $order, Request $request)
    {
        // 判断订单是否属于当前用户
        $this->authorize('own', $order);
        // 订单已支付或已关闭
        if ($order->paid_at || $order->closed) {
            throw new InvalidRequestException('订单状态不正确');
        }

        // 调用支付宝的网页支付
        return app('alipay')->web([
            'out_trade_no' => $order->no,                                 //订单编号, 需保证在商户端不重复
            'total_amount' => $order->total_amount,                       // 订单金额,单位元, 支持小数点后两位
            'subject'      => '支付 Laravel shop 的订单: '.$order->no,     //订单标题
        ]);
    }

    // 支付宝前端回调
    public function alipayReturn()
    {
        try {
            app('alipay')->verify();
        } catch (\Exception $e) {
            return view('pages.error', ['msg' => '数据不正确']);
        }

        return view('pages.success', ['msg' => '付款成功']);
    }

    // 支付宝后端回调
    public function alipayNotify()
    {
        // 校验输入参数
        $data = app('alipay')->verify();

        // $data->out_trade_no 流水订单号
        $order = Order::where('no', $data->out_trade_no)->first();
        // 判断该订单是否存在
        if (!$order) {
            return 'fail';
        }

        // 判断订单是否已经支付
        if ($order->paid_at) {
          // 返回数据给支付宝
          return app('alipay')->success();
        }

        $order->update([
            'paid_at' => Carbon::now(),            // 支付时间
            'payment_method' => 'alipay',          // 支付方式
            'payment_no'     => $data->trade_no,   // 支付宝订单号
        ]);

        $this->afterPaid($order);
        return app('alipay')->success();
    }

    protected function afterPaid(Order $order)
    {
        event(new OrderPaid($order));
    }
}
