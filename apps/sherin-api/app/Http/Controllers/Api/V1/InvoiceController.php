<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\StoreInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Services\InvoiceService;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Invoice::class);

        $query = Invoice::query()
            ->with(['customer', 'employer', 'store'])
            ->orderByDesc('invoice_date');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('status')) {
            $query->where('status', (int) $request->input('status'));
        }

        $invoices = $query->paginate($request->integer('per_page', 15));

        return InvoiceResource::collection($invoices);
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $invoice = $this->invoiceService->create($request->user(), $request->validated());

        return (new InvoiceResource($invoice))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Invoice $invoice): InvoiceResource
    {
        $this->authorize('view', $invoice);

        $invoice->load($this->invoiceRelations());

        return new InvoiceResource($invoice);
    }

    public function print(Request $request, Invoice $invoice): InvoiceResource
    {
        $this->authorize('view', $invoice);

        $invoice->load($this->invoiceRelations());

        return new InvoiceResource($invoice);
    }

    /**
     * @return list<string>
     */
    private function invoiceRelations(): array
    {
        return ['customer', 'employer', 'items.product', 'store'];
    }

    public function cancel(Request $request, Invoice $invoice): InvoiceResource
    {
        $this->authorize('cancel', $invoice);

        $invoice = $this->invoiceService->cancel($invoice);

        return new InvoiceResource($invoice);
    }
}
