<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProductReserve;
use App\ProductSize;
use App\Product;

class cronPendingReserves extends Command
{
    protected $signature = 'cronPendingReserves';
    protected $description = 'cronPendingReserves';

    public function handle()
    {
        $reserves = ProductReserve::whereHas('product', function ($query) {
    			$query->withTrashed()
    				->withoutGlobalScopes(['active', 'active-store']);
    		})
    		->with(['product' => function($query) {
    			$query->withTrashed()
    				->withoutGlobalScopes(['active', 'active-store']);
    		}])
            ->where('status', 2)
            ->whereRaw('TIMESTAMPDIFF(DAY, created_at, NOW()) >= 2')
            ->get();

        foreach ($reserves as $reserve) {
    		$r = ProductReserve::find($reserve->id);
    		$r->status = 0;
            $r->confirmed_at = date('Y-m-d H:i:s');
            $r->token = null;

    		if ($r->save()) {
    			// Desactive product or size
    			if ($r->size) {
    				ProductSize::whereHas('product', function ($query) {
    						$query->withTrashed()
                                ->withoutGlobalScopes(['active', 'active-store']);
    					})
    					->where('product_id', $r->product_id)
    					->where('size', $r->size)
    					->delete();

    				$count_sizes = ProductSize::whereHas('product', function ($query) {
    						$query->withTrashed()
                                ->withoutGlobalScopes(['active', 'active-store']);
    					})
    					->where('product_id', $r->product_id)
    					->count();
    			}

    			if (!$r->size || $r->size && !$count_sizes) {
    				$product = Product::withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store'])
    					->where('id', $r->product_id)
    					->first();

    				$product->status = 0;
    				$product->save();
    			}

                Mail::send('emails.store-pending-reserve', ['reserve' => $r], function($q) use($r) {
                    $q->from('no-reply@naslojas.com', 'naslojas');
                    $q->to($r->product->store->user->first()->email);
                    $q->subject('Reserva de produto');
                });

                Mail::send('emails.client-pending-reserve', ['reserve' => $r], function($q) use($r) {
                    $q->from('no-reply@naslojas.com', 'naslojas');
                    $q->to($r->client->email);
                    $q->subject('Reserva de produto');
                });
    		}
    	}
    }
}
