<?php

namespace crocodicstudio\crocoding;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class CrocodingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        require __DIR__.'/helpers/Helper.php';
        $this->mergeConfigFrom(__DIR__.'/configs/crocoding.php','crocoding');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);
        $this->loadViewsFrom(__DIR__.'/views', 'crocoding');
        $app  = $this->app;
        require __DIR__.'/routes.php';
    }
}
