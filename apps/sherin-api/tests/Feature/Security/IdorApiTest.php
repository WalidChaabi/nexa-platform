<?php

namespace Tests\Feature\Security;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IdorApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_employer_cannot_access_other_store_customer_via_api(): void
    {
        [$employer, $otherCustomer] = $this->employerWithForeignResource(Customer::class);

        $this->actingAs($employer)
            ->getJson("/api/v1/customers/{$otherCustomer->id}")
            ->assertForbidden();

        $this->actingAs($employer)
            ->putJson("/api/v1/customers/{$otherCustomer->id}", ['phone' => '0600000001'])
            ->assertForbidden();

        $this->actingAs($employer)
            ->deleteJson("/api/v1/customers/{$otherCustomer->id}")
            ->assertForbidden();
    }

    public function test_employer_cannot_access_other_store_product_via_api(): void
    {
        [$employer, $otherProduct] = $this->employerWithForeignResource(Product::class);

        $this->actingAs($employer)
            ->getJson("/api/v1/products/{$otherProduct->id}")
            ->assertForbidden();

        $this->actingAs($employer)
            ->putJson("/api/v1/products/{$otherProduct->id}", ['label' => 'Blocked'])
            ->assertForbidden();

        $this->actingAs($employer)
            ->deleteJson("/api/v1/products/{$otherProduct->id}")
            ->assertForbidden();
    }

    public function test_employer_cannot_access_other_store_rdv_via_api(): void
    {
        [$employer, $otherRdv] = $this->employerWithForeignResource(Rdv::class);

        $this->actingAs($employer)
            ->getJson("/api/v1/rdvs/{$otherRdv->id}")
            ->assertForbidden();

        $this->actingAs($employer)
            ->putJson("/api/v1/rdvs/{$otherRdv->id}", ['title' => 'Blocked'])
            ->assertForbidden();

        $this->actingAs($employer)
            ->deleteJson("/api/v1/rdvs/{$otherRdv->id}")
            ->assertForbidden();
    }

    public function test_owner_cannot_access_other_owner_store_customer(): void
    {
        $owner = User::factory()->owner()->create();
        Store::factory()->create(['user_id' => $owner->id]);
        $foreignCustomer = Customer::factory()->create();

        $this->actingAs($owner)
            ->getJson("/api/v1/customers/{$foreignCustomer->id}")
            ->assertForbidden();
    }

    public function test_owner_cannot_cancel_invoice_for_unowned_store(): void
    {
        $owner = User::factory()->owner()->create();
        Store::factory()->create(['user_id' => $owner->id]);
        $foreignInvoice = Invoice::factory()->forStore(Store::factory()->create())->create();

        $this->actingAs($owner)
            ->postJson("/api/v1/invoices/{$foreignInvoice->id}/cancel")
            ->assertForbidden();
    }

    /**
     * @template T of Customer|Product|Rdv
     * @param  class-string<T>  $modelClass
     * @return array{0: User, 1: T}
     */
    private function employerWithForeignResource(string $modelClass): array
    {
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $storeA->id]);

        $foreign = match ($modelClass) {
            Customer::class => Customer::factory()->create(['store_id' => $storeB->id]),
            Product::class => Product::factory()->create(['store_id' => $storeB->id]),
            Rdv::class => Rdv::factory()->create(['store_id' => $storeB->id]),
            default => throw new \InvalidArgumentException("Unsupported model: {$modelClass}"),
        };

        return [$employer, $foreign];
    }
}
