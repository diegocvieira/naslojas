<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProductSize;
use App\Product;
use App\OrderProducts;
use Mail;
use App\Mail\OrderRefuseMail;
use App\Mail\ProductDisableMail;

class cronPendingOrders extends Command
{
    protected $signature = 'cronPendingOrders';
    protected $description = 'cronPendingOrders';

    public function handle()
    {
        $orders = OrderProducts::whereHas('product', function ($query) {
                $query->withoutGlobalScopes(['active', 'active-store'])
                    ->withTrashed();
            })
            ->with(['product' => function($query) {
    			$query->withTrashed()
    				->withoutGlobalScopes(['active', 'active-store']);
    		}])
            ->where('status', 2)
            ->get();

        $emails_client = [];
        $emails_store = [];

        foreach ($orders as $o) {
            $order = OrderProducts::whereHas('product', function ($query) {
                    $query->withoutGlobalScopes(['active', 'active-store'])
                        ->withTrashed();
                })
                ->with(['product' => function($query) {
        			$query->withTrashed()
        				->withoutGlobalScopes(['active', 'active-store']);
        		}])
                ->find($o->id);

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

                    $product = Product::doesnthave('sizes')
                        ->withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store'])
                        ->where('id', $order->product_id)
                        ->first();

                    if ($product) {
                        $product->status = 0;
                        $product->save();
                    }

                    if (!in_array($order->order->client->email, $emails_client)) {
                        $emails_client[] = $order->order->client->email;
                    }

                    if (!in_array($order->product->store->user->first()->email, $emails_store)) {
                        $emails_store[] = $order->product->store->user->first()->email;
                    }
                }
            }
        }

        if ($emails_client) {
            Mail::to($emails_client)->send(new OrderRefuseMail());
        }

        if ($emails_store) {
            Mail::to($emails_store)->send(new ProductDisableMail());
        }
    }
}
