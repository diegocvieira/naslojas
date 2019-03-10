<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProductSize;
use App\Product;
use App\OrderProducts;
use Mail;

class cronPendingOrders extends Command
{
    protected $signature = 'cronPendingOrders';
    protected $description = 'cronPendingOrders';

    public function handle()
    {
        $orders = OrderProducts::where('status', 2)->get();

        foreach ($orders as $o) {
            $order = OrderProducts::find($o->id);

            if (strtotime(_businessDay($order->created_at, true)) <= strtotime(date('Y-m-d'))) {
                $order->status = 3;

                if ($order->save()) {
                    ProductSize::whereHas('product', function ($query) {
                            $query->withTrashed()
                                ->withoutGlobalScopes(['active', 'active-store']);
                        })
                        ->where('product_id', $order->product_id)
                        ->where('size', $order->size)
                        ->delete();

                    $count_sizes = ProductSize::whereHas('product', function ($query) {
                            $query->withTrashed()
                                ->withoutGlobalScopes(['active', 'active-store']);
                        })
                        ->where('product_id', $order->product_id)
                        ->count();

                    if (!$count_sizes) {
                        $product = Product::withTrashed()
                            ->withoutGlobalScopes(['active', 'active-store'])
                            ->where('id', $order->product_id)
                            ->first();

                        $product->status = 0;
                        $product->save();
                    }

                    Mail::send('emails.order-refuse', [], function ($q) use ($order) {
                        $q->from('no-reply@naslojas.com', 'naslojas');
                        $q->to($order->order->client->email);
                        $q->subject('Pedido recusado');
                    });
                }
            }
        }
    }
}
