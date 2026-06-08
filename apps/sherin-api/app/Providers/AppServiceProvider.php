<?php

namespace App\Providers;

use App\Contracts\Integrations\PaymentGateway;
use App\Contracts\Integrations\SmsGateway;
use App\Integrations\Payment\CmiPaymentGateway;
use App\Integrations\Sms\BulkSmsGateway;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\CampaignHistorique;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Store;
use App\Policies\CampaignDetailPolicy;
use App\Policies\CampaignHistoriquePolicy;
use App\Policies\CampaignPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentHistoryPolicy;
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
        PaymentHistory::class => PaymentHistoryPolicy::class,
        Customer::class => CustomerPolicy::class,
        Category::class => CategoryPolicy::class,
        Product::class => ProductPolicy::class,
        Campaign::class => CampaignPolicy::class,
        CampaignDetail::class => CampaignDetailPolicy::class,
        CampaignHistorique::class => CampaignHistoriquePolicy::class,
        Rdv::class => RdvPolicy::class,
    ];
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SmsGateway::class, BulkSmsGateway::class);
        $this->app->bind(PaymentGateway::class, CmiPaymentGateway::class);
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
