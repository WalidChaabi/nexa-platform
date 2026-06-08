<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Store;
use App\Policies\CampaignPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\ProductPolicy;
use App\Policies\RdvPolicy;
use App\Policies\StorePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Store::class => StorePolicy::class,
        Invoice::class => InvoicePolicy::class,
        Customer::class => CustomerPolicy::class,
        Product::class => ProductPolicy::class,
        Campaign::class => CampaignPolicy::class,
        Rdv::class => RdvPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
