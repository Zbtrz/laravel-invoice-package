<?php

namespace Zbtrz\Invoice;

use Illuminate\Support\ServiceProvider;

class InvoiceServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'invoice');
        $this->mergeConfigFrom(__DIR__ . '/config/invoice.php', 'invoice');
        $this->publishes([
            __DIR__ . '/config/invoice.php' => config_path('invoice.php'),
        ]);

    }

    public function register()
    {

    }
}
