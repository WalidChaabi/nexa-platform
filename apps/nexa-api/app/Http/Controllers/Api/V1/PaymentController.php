<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PaymentHistoryResource;
use App\Models\PaymentHistory;
use App\Services\PaymentCheckoutService;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentCheckoutService $checkoutService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', PaymentHistory::class);

        $query = PaymentHistory::query()
            ->with('owner')
            ->orderByDesc('id');

        if (! StoreAccess::isAdmin($request->user())) {
            $query->where('owner_id', $request->user()->id);
        }

        $payments = $query->paginate($request->integer('per_page', 15));

        return PaymentHistoryResource::collection($payments);
    }

    public function show(Request $request, PaymentHistory $paymentHistory): PaymentHistoryResource
    {
        $this->authorize('view', $paymentHistory);

        $paymentHistory->load('owner');

        return new PaymentHistoryResource($paymentHistory);
    }

    public function preview(Request $request): JsonResponse
    {
        $this->authorize('checkout', PaymentHistory::class);

        return response()->json([
            'data' => $this->checkoutService->preview($request->user()),
        ]);
    }

    public function checkout(Request $request): JsonResponse
    {
        $this->authorize('checkout', PaymentHistory::class);

        $redirect = $this->checkoutService->initiate($request->user());

        return response()->json([
            'data' => [
                'action_url' => $redirect->actionUrl,
                'fields' => $redirect->fields,
            ],
        ]);
    }

    public function cmiCallback(Request $request): Response
    {
        $response = $this->checkoutService->handleCallback($request->all());

        return response($response, 200, ['Content-Type' => 'text/plain']);
    }
}
