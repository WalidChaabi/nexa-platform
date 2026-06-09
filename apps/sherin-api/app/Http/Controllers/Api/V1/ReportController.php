<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Reports\ReportFilterRequest;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function __construct(private readonly ReportService $reports) {}

    public function sales(ReportFilterRequest $request): JsonResponse
    {
        $data = $this->reports->salesReport(
            (int) $request->input('store_id'),
            Carbon::parse($request->input('from')),
            Carbon::parse($request->input('to')),
            $request->filled('for_sexe') ? (int) $request->input('for_sexe') : null,
        );

        return response()->json(['data' => $data]);
    }

    public function customers(ReportFilterRequest $request): JsonResponse
    {
        $data = $this->reports->customersReport(
            (int) $request->input('store_id'),
            Carbon::parse($request->input('from')),
            Carbon::parse($request->input('to')),
        );

        return response()->json(['data' => $data]);
    }

    public function products(ReportFilterRequest $request): JsonResponse
    {
        $data = $this->reports->productsReport(
            (int) $request->input('store_id'),
            Carbon::parse($request->input('from')),
            Carbon::parse($request->input('to')),
        );

        return response()->json(['data' => $data]);
    }

    public function export(Request $request, string $type): Response
    {
        $validated = validator($request->all(), [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'from' => ['required', 'date'],
            'to' => ['required', 'date', 'after_or_equal:from'],
            'for_sexe' => ['nullable', 'integer', 'in:0,1,2'],
        ])->validate();

        $storeId = (int) $validated['store_id'];
        $from = Carbon::parse($validated['from']);
        $to = Carbon::parse($validated['to']);

        if (! \App\Support\Authorization\StoreAccess::canAccessStore($request->user(), $storeId)) {
            abort(403, 'You do not have access to this store.');
        }

        $report = match ($type) {
            'sales' => $this->reports->salesReport(
                $storeId,
                $from,
                $to,
                isset($validated['for_sexe']) ? (int) $validated['for_sexe'] : null,
            ),
            'customers' => $this->reports->customersReport($storeId, $from, $to),
            'products' => $this->reports->productsReport($storeId, $from, $to),
            default => abort(404, 'Unknown report type.'),
        };

        $html = View::make('reports.print', [
            'type' => $type,
            'report' => $report,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ])->render();

        $filename = sprintf(
            'nexa-%s-%s-%s.html',
            $type,
            $report['store']['id'],
            now()->format('Ymd'),
        );

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
