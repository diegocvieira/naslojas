<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ProductConfirm;
use App\ProductSize;
use App\Product;
use Mail;

class cronPendingConfirms extends Command
{
    protected $signature = 'cronPendingConfirms';
    protected $description = 'cronPendingConfirms';

    public function handle()
    {
        $confirms = ProductConfirm::whereHas('product', function ($query) {
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

        foreach ($confirms as $confirm) {
    		$c = ProductConfirm::find($confirm->id);
    		$c->status = 0;
            $c->confirmed_at = date('Y-m-d H:i:s');
            $c->token = null;

    		if ($c->save()) {
    			// Desactive product or size
    			if ($c->size) {
    				ProductSize::whereHas('product', function ($query) {
    						$query->withTrashed()
                                ->withoutGlobalScopes(['active', 'active-store']);
    					})
    					->where('product_id', $c->product_id)
    					->where('size', $c->size)
    					->delete();

    				$count_sizes = ProductSize::whereHas('product', function ($query) {
    						$query->withTrashed()
                                ->withoutGlobalScopes(['active', 'active-store']);
    					})
    					->where('product_id', $c->product_id)
    					->count();
    			}

    			if (!$c->size || $c->size && !$count_sizes) {
    				$product = Product::withTrashed()
                        ->withoutGlobalScopes(['active', 'active-store'])
    					->where('id', $c->product_id)
    					->first();

    				$product->status = 0;
    				$product->save();
    			}

                Mail::send('emails.store-pending-confirm', ['confirm' => $c], function($q) use($c) {
                    $q->from('no-reply@naslojas.com', 'naslojas');
                    $q->to($c->product->store->user->first()->email);
                    $q->subject('Confirmação de produto');
                });

                Mail::send('emails.client-pending-confirm', ['confirm' => $c], function($q) use($c) {
                    $q->from('no-reply@naslojas.com', 'naslojas');
                    $q->to($c->client->email);
                    $q->subject('Confirmação de produto');
                });
    		}
    	}
    }
}
