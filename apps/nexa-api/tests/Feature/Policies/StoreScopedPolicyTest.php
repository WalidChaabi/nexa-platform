<?php

namespace Tests\Feature\Policies;

use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreScopedPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_any_store_resource(): void
    {
        $admin = User::factory()->admin()->create();
        $store = Store::factory()->create();
        $invoice = new Invoice(['store_id' => $store->id]);

        $this->assertTrue($admin->can('view', $store));
        $this->assertTrue($admin->can('view', $invoice));
    }

    public function test_employer_cannot_view_other_store_invoice(): void
    {
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $storeA->id]);
        $invoice = new Invoice(['store_id' => $storeB->id]);

        $this->assertFalse($employer->can('view', $invoice));
    }

    public function test_owner_can_view_own_store_but_not_other_store(): void
    {
        $owner = User::factory()->owner()->create();
        $ownStore = Store::factory()->create(['user_id' => $owner->id]);
        $otherStore = Store::factory()->create();

        $this->assertTrue($owner->can('view', $ownStore));
        $this->assertFalse($owner->can('view', $otherStore));
    }

    public function test_only_admin_can_delete_store(): void
    {
        $admin = User::factory()->admin()->create();
        $owner = User::factory()->owner()->create();
        $store = Store::factory()->create(['user_id' => $owner->id]);

        $this->assertTrue($admin->can('delete', $store));
        $this->assertFalse($owner->can('delete', $store));
    }

    public function test_store_scoped_models_deny_cross_store_access(): void
    {
        $storeA = Store::factory()->create();
        $storeB = Store::factory()->create();
        $employer = User::factory()->employer()->create(['store_id' => $storeA->id]);

        $resources = [
            new Customer(['store_id' => $storeB->id]),
            new Product(['store_id' => $storeB->id]),
            new Rdv(['store_id' => $storeB->id]),
        ];

        foreach ($resources as $resource) {
            $this->assertFalse($employer->can('view', $resource));
            $this->assertFalse($employer->can('update', $resource));
        }
    }
}
