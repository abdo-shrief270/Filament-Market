<?php

namespace App\Events;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Order $order)
    {
        if($order){
            $total_price=0;
            foreach (OrderDetail::where('order_id',$order->id)->get() as $item){
                $total_price += $item->product->price * $item->quantity;
            }
            if($order->discount_type=='amount'){
                $total_price -= $order->discount;
            }else{
                $total_price *= 1-($order->discount/100);
            }
            Order::where('id',$order->id)->update(
                [
                    'total_price' => $total_price + $order->customer->city->shipping_cost
                ]
            );
        }
    }

}
