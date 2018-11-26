<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Using class based composers...
        //view()->composer(['inc/footer'], 'App\Http\ViewComposers\CitiesComposer');

        view()->composer(['home', 'store.show'], 'App\Http\ViewComposers\FiltersComposer');

        view()->composer(['store.product-edit', 'store.show'], 'App\Http\ViewComposers\SelectsProductComposer');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
