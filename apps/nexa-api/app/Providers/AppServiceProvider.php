<?php

namespace App\Providers;

use App\Contracts\Integrations\PaymentGateway;
use App\Contracts\Integrations\SmsGateway;
use App\Enums\UserRole;
use App\Integrations\Payment\CmiPaymentGateway;
use App\Integrations\Sms\BulkSmsGateway;
use App\Models\Activity;
use App\Models\Campaign;
use App\Models\CampaignDetail;
use App\Models\CampaignHistorique;
use App\Models\Category;
use App\Models\CrossProduct;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\PaymentHistory;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\SmsCrossSelling;
use App\Models\Store;
use App\Models\User;
use App\Policies\ActivityPolicy;
use App\Policies\CampaignDetailPolicy;
use App\Policies\CampaignHistoriquePolicy;
use App\Policies\CampaignPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CrossProductPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentHistoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RdvPolicy;
use App\Policies\SmsCrossSellingPolicy;
use App\Policies\StorePolicy;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activity::class => ActivityPolicy::class,
        Store::class => StorePolicy::class,
        Invoice::class => InvoicePolicy::class,
        PaymentHistory::class => PaymentHistoryPolicy::class,
        Customer::class => CustomerPolicy::class,
        Category::class => CategoryPolicy::class,
        CrossProduct::class => CrossProductPolicy::class,
        Product::class => ProductPolicy::class,
        SmsCrossSelling::class => SmsCrossSellingPolicy::class,
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
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        Route::bind('owner', function (string $value): User {
            return User::query()
                ->where('is_admin', UserRole::Owner->value)
                ->findOrFail($value);
        });

        Route::bind('employer', function (string $value): User {
            return User::query()
                ->where('is_admin', UserRole::Employer->value)
                ->findOrFail($value);
        });
    }
}
