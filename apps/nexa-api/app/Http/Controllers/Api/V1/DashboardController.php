<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\DashboardKpiRequest;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard) {}

    public function kpis(DashboardKpiRequest $request): JsonResponse
    {
        $storeId = $request->filled('store_id')
            ? (int) $request->input('store_id')
            : null;

        return response()->json([
            'data' => $this->dashboard->kpis($request->user(), $storeId),
        ]);
    }
}
