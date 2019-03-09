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

        view()->composer(['home', 'store.show', 'search', 'mobile.inc.top-nav'], 'App\Http\ViewComposers\FiltersComposer');

        view()->composer(['store.list-product-edit', 'mobile.store.create-edit-product'], 'App\Http\ViewComposers\SelectsProductComposer');

        view()->composer(['inc.top-nav-store', 'mobile.inc.top-nav'], 'App\Http\ViewComposers\ListStoresComposer');

        view()->composer(['inc.top-nav', 'mobile.inc.top-nav'], 'App\Http\ViewComposers\CountBagComposer');
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
