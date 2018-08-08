<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use App\Events\OrderReviewd;
use App\Services\OrderService;
use App\Http\Requests\OrderRequest;
use App\Exceptions\InternalException;
use App\Http\Requests\SendReviewRequest;
use App\Http\Requests\ApplyRefundRequest;
use App\Http\Requests\Admin\HandleRefundRequest;

class OrdersController extends Controller
{
    public function store(OrderRequest $request, OrderService $orderService)
    {
        $user    = $request->user();
        $address = UserAddress::find($request->input('address_id'));

        return $orderService->store($user, $address, $request->input('remark'), $request->input('items'));
    }

    public function index(Request $request)
    {
        $orders = Order::query()
        // 预加载,避免 N+1
            ->with(['items.product', 'items.productSku'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order, Request $request)
    {
        $this->authorize('own', $order);
        return view('orders.show', ['order' => $order->load(['items.productSku','items.product'])]);
    }

    // 用户收货
    public function received(Order $order, Request $request)
    {
        // 校验权限
        $this->authorize('own', $order);

        // 判断订单状态是否为已发货
        if ($order->ship_status !== Order::SHIP_STATUS_DELIVERED) {
          throw new InvalidRequestException('发货状态不正确');
        }

        // 更新发货状态为已收到
        $order->update(['ship_status' => Order::SHIP_STATUS_RECEIVED]);

        return $order;
    }

    // 评价页面
    public function review(order $order)
    {
        // 校验权限
        $this->authorize('own', $order);

        // 判断是否已经支付
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付, 不可评价');
        }

        // 使用 load 方法加载关联数据, 避免 N+1 问题
        return view('orders.review', ['order' => $order->load(['items.productSku', 'items.product'])]);
    }

    // 提交评价接口
    public function sendReview(Order $order, SendReviewRequest $request)
    {
        // 校验权限
        $this->authorize('own', $order);

        // 判断是否支付
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付, 不可支付');
        }

        // 判断是否已经评价
        if ($order->reviewed) {
            throw new InvalidRequestException('该订单已评价, 不可重复提交');
        }

        $reviews = $request->input('reviews');

        // 开启事务
        \DB::transaction(function() use ($reviews, $order) {
            // 遍历用户提交的数据
            foreach ($reviews as $review) {
                $orderItem = $order->items()->find($review['id']);

                // 保存评分和评价
                $orderItem->update([
                    'rating'      => $review['rating'],
                    'review'      => $review['review'],
                    'reviewed_at' => Carbon::now(),
                ]);
            }

            // 将订单标记为已评价
            $order->update(['reviewed' => true]);

            event(new OrderReviewd($order));
        });

        return redirect()->back();
    }

    public function applyRefund(Order $order, ApplyRefundRequest $request)
    {
        // 校验订单是否属于当前用户
        $this->authorize('own', $order);
        // 判断订单是否已付款
        if (!$order->paid_at) {
            throw new InvalidRequestException('该订单未支付, 不可退款');
        }

        // 判断订单退款状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_PENDING) {
            throw new InvalidRequestException('该订单已申请过退款, 请耐心等待');
        }

        // 将用户申请退款的理由放到订单的 extra 字段中
        $extra = $order->extra ?: [];
        $extra['refund_reason'] = $request->input('reason');

        $order->update([
            'refund_status' => Order::REFUND_STATUS_APPLIED,
            'extra'         => $extra,
        ]);

        return $order;
    }

    public function handleRefund(Order $order, HandleRefundRequest $request)
    {
        // 判断订单状态是否正确
        if ($order->refund_status !== Order::REFUND_STATUS_APPLIED)
        {
            throw new InvalidRequestException('订单状态不正确');
        }

        // 是否同意退款
        if ($request->input('agree')) {
            // 调用退款逻辑
            $this->_refundOrder($order);
        } else {
            // 将拒绝退款的理由放到 extra 字段中
            $extra = $order->extra ?: [];
            $extra['refund_disagree_reason'] = $request->input('reason');
            // 将订单的退款状态改为未退款
            $order->update([
                'refund_status' => Order::REFUND_STATUS_PENDING,
                'extra'         => $extra,
            ]);
        }

        return $order;
    }

    protected function _refundOrder(Order $order)
    {
        // 判断该订单的支付方式
        switch ($order->payment_method) {
            case 'wechat':
              break;
            case 'alipay':
              // 生成一个退款订单号
              $refundNo = Order::getAvaliableRefundNo();
              // 调用支付宝支付实例的 refund 方法
              $ret = app('alipay')->refund([
                  'out_trade_no'   => $order->no,            // 之前的流水单号
                  'refund_amount'  => $order->total_amount,  // 退款金额, 单位元
                  'out_request_no' => $refundNo,             // 退款订单号
              ]);
              // 根据支付宝文档, 如果返回值中有 sub_code 字段说明退款失败
              if ($ret->sub_code) {
                  // 将退款失败的存入 extra 字段
                  $extra = $order->extra;
                  $extra['refund_failed_code'] = $ret->sub_code;
                  // 将退款标记为退款失败
                  $order->update([
                      'refund_no'     => $refundNo,
                      'refund_status' => Order::REFUND_STATUS_FAILED,
                      'extra'         => $extra,
                  ]);
              } else {
                  // 将订单的退款状态更新为已退款
                  $order->update([
                      'refund_no' => $refundNo,
                      'refund_status' => Order::REFUND_STATUS_SUCCESS,
                  ]);
              }
              break;
            default:
              // 健壮性
              throw new InternalException('未知支付方式:'.$order->payment_method);
              break;
        }

    }
}
