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
        view()->composer(['inc.header', 'mobile.inc.header'], 'App\Http\ViewComposers\CitiesComposer');

        view()->composer(['mobile.inc.header'], 'App\Http\ViewComposers\FiltersComposer');

        view()->composer(['store.list-product-edit', 'mobile.store.create-edit-product'], 'App\Http\ViewComposers\SelectsProductComposer');

        view()->composer(['inc.header', 'mobile.inc.header'], 'App\Http\ViewComposers\BagComposer');
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
