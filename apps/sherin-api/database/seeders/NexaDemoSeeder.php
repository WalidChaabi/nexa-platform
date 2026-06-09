<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Epingles;
use App\Models\Invoice;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;

class NexaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->where('email', 'owner@nexa.test')->first();
        $employer = User::query()->where('email', 'employer@nexa.test')->first();

        if (! $owner || ! $employer) {
            return;
        }

        $store = Store::query()->firstOrCreate(
            ['user_id' => $owner->id, 'name' => 'Demo Store'],
            [
                'activity_id' => 1,
                'phone' => '0612345678',
                'contact' => 'Demo Owner',
                'address' => 'Casablanca',
                'status' => 1,
                'sender_id' => 'DEMO',
            ],
        );

        $employer->update(['store_id' => $store->id]);

        Epingles::query()->firstOrCreate(
            ['store_id' => $store->id, 'name' => 'Coupe'],
            ['color' => '#3b82f6'],
        );
        Epingles::query()->firstOrCreate(
            ['store_id' => $store->id, 'name' => 'Coloration'],
            ['color' => '#ef4444'],
        );

        $customer = Customer::query()->firstOrCreate(
            ['store_id' => $store->id, 'phone' => '0698765432'],
            [
                'firstname' => 'Amina',
                'lastname' => 'Client',
                'email' => 'amina@example.test',
            ],
        );

        $product = Product::query()->firstOrCreate(
            ['store_id' => $store->id, 'label' => 'Coupe + Brushing'],
            [
                'price' => 250,
                'promotion_price' => 250,
                'quantite' => 100,
            ],
        );

        if (Invoice::query()->where('store_id', $store->id)->exists()) {
            return;
        }

        $invoice = Invoice::query()->create([
            'store_id' => $store->id,
            'user_id' => $owner->id,
            'employ_id' => $employer->id,
            'customer_id' => $customer->id,
            'for_sexe' => 1,
            'description' => 0,
            'invoice_date' => now()->subDay(),
            'total' => 250,
            'total_ht' => 208.33,
            'paid_amount' => 250,
            'payment_method' => 0,
            'points' => 0,
            'status' => 0,
            'reste' => 0,
            'reste_payer' => 0,
        ]);

        InvoiceProduct::query()->create([
            'invoice_id' => $invoice->id,
            'product_id' => $product->id,
            'qte' => 1,
            'price' => 250,
        ]);
    }
}
