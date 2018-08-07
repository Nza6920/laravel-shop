<?php

namespace App\Listeners;

use App\Models\OrderItem;
use App\Events\OrderPaid;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateProductSoldCount implements ShouldQueue
{
    public function handle(OrderPaid $event)
    {
        // 从事件对象中取出相应订单
        $order = $event->getOrder();
        // 循环遍历出商品
        foreach ($order->items as $item) {
          $product = $item->product;
          // 计算商品销量
          $soldCount = OrderItem::query()
              ->where('product_id', $product->id)
              ->whereHas('order', function ($query) {
                $query->whereNotNull('paid_at');      // 找到关联的已支付的订单
              })->sum('amount');

          // 更新商品销量
          $product->update([
              'sold_count' => $soldCount,
          ]);
        }
    }
}
