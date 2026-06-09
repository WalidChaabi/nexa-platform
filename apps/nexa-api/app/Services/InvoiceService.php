<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\InvoicePaiement;
use App\Models\InvoiceProduct;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InvoiceService
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function create(User $actor, array $data): Invoice
    {
        return DB::transaction(function () use ($actor, $data): Invoice {
            /** @var Store $store */
            $store = Store::query()->findOrFail($data['store_id']);

            $paidAmount = (float) ($data['paid_amount'] ?? 0);
            $total = (float) $data['total'];
            $reste = $store->reste === 1 ? max(0, $total - $paidAmount) : 0;

            $invoice = Invoice::query()->create([
                'store_id' => $store->id,
                'user_id' => $store->user_id,
                'employ_id' => $actor->id,
                'customer_id' => $data['customer_id'],
                'for_sexe' => (int) ($data['for_sexe'] ?? 0),
                'description' => (int) ($data['description'] ?? 0),
                'invoice_date' => now(),
                'total' => $total,
                'total_ht' => (float) ($data['total_ht'] ?? 0),
                'tva' => (float) ($data['tva'] ?? 0),
                'paid_amount' => $paidAmount,
                'payment_method' => (int) ($data['payment_method'] ?? 0),
                'points' => (int) ($data['points'] ?? 0),
                'status' => InvoiceStatus::Pending->value,
                'reste' => $reste,
                'reste_payer' => (float) ($data['reste_payer'] ?? 0),
            ]);

            foreach ($data['products'] as $line) {
                $product = Product::query()->findOrFail($line['product_id']);

                InvoiceProduct::query()->create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $product->id,
                    'qte' => (int) $line['qte'],
                    'price' => (float) ($line['price'] ?? $product->price),
                ]);
            }

            if ($paidAmount > 0) {
                InvoicePaiement::query()->create([
                    'invoice_id' => $invoice->id,
                    'amount' => $paidAmount,
                    'date' => now(),
                ]);
            }

            return $invoice->load(['customer', 'employer', 'items.product', 'store']);
        });
    }

    public function cancel(Invoice $invoice): Invoice
    {
        if (! $invoice->isPending()) {
            throw ValidationException::withMessages([
                'status' => 'Only pending invoices can be cancelled.',
            ]);
        }

        $invoice->update([
            'status' => InvoiceStatus::Cancelled->value,
        ]);

        return $invoice->fresh(['customer', 'employer', 'items.product', 'store']);
    }
}
