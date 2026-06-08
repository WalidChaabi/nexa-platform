<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $file = app_path('Helpers/Helper.php');
        $helperPaginator=app_path('Helpers/CollectionHelper');
        if (file_exists($file)) {
            require_once($file);
        }
        if (file_exists($helperPaginator)) {
            require_once($helperPaginator);
        }
    }
}
