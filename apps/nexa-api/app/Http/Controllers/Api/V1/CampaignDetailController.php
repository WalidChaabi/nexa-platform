<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Campaigns\StoreCampaignDetailRequest;
use App\Http\Requests\Campaigns\UpdateCampaignDetailRequest;
use App\Http\Resources\CampaignDetailResource;
use App\Models\CampaignDetail;
use App\Models\Filter;
use App\Models\Message;
use App\Models\MessageCampaign;
use App\Services\CampaignFilterService;
use App\Support\Authorization\StoreAccess;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;

class CampaignDetailController extends Controller
{
    public function __construct(private readonly CampaignFilterService $filterService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', CampaignDetail::class);

        $query = CampaignDetail::query()
            ->with(['campaign', 'filter', 'store', 'product'])
            ->orderByDesc('id');

        StoreAccess::applyStoreScope($query, $request->user());

        if ($request->filled('store_id')) {
            $storeId = (int) $request->input('store_id');

            if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
                abort(403, 'You do not have access to this store.');
            }

            $query->where('store_id', $storeId);
        }

        if ($request->filled('campaign_id')) {
            $query->where('campaign_id', (int) $request->input('campaign_id'));
        }

        if ($request->filled('active')) {
            $query->where('active', (int) $request->input('active'));
        }

        $details = $query->paginate($request->integer('per_page', 15));

        return CampaignDetailResource::collection($details);
    }

    public function filterDefaults(Request $request): JsonResponse
    {
        $this->authorize('viewAny', CampaignDetail::class);

        $request->validate([
            'store_id' => ['required', 'integer', 'exists:stores,id'],
        ]);

        $storeId = (int) $request->input('store_id');

        if (! StoreAccess::canAccessStore($request->user(), $storeId)) {
            abort(403, 'You do not have access to this store.');
        }

        return response()->json([
            'data' => $this->filterService->defaultsForStore($storeId),
        ]);
    }

    public function store(StoreCampaignDetailRequest $request): JsonResponse
    {
        $detail = DB::transaction(function () use ($request) {
            $filter = Filter::query()->create($this->normalizeFilterInput($request->input('filter', [])));

            $detail = CampaignDetail::query()->create([
                'store_id' => (int) $request->input('store_id'),
                'campaign_id' => (int) $request->input('campaign_id'),
                'filter_id' => $filter->id,
                'product_id' => $request->input('product_id'),
                'reduction' => $request->integer('reduction'),
                'active' => $request->integer('active', 1),
            ]);

            $this->syncMessages($detail, $request->input('messages', []));

            return $detail;
        });

        $detail->load(['campaign', 'filter', 'store', 'product', 'messageCampaigns.message']);

        return (new CampaignDetailResource($detail))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, CampaignDetail $campaignDetail): CampaignDetailResource
    {
        $this->authorize('view', $campaignDetail);

        $campaignDetail->load(['campaign', 'filter', 'store', 'product', 'messageCampaigns.message']);
        $campaignDetail->audience_count = $this->filterService->countAudience(
            $campaignDetail->filter,
            (int) $campaignDetail->store_id,
        );

        return new CampaignDetailResource($campaignDetail);
    }

    public function update(UpdateCampaignDetailRequest $request, CampaignDetail $campaignDetail): CampaignDetailResource
    {
        $campaignDetail = DB::transaction(function () use ($request, $campaignDetail) {
            $campaignDetail->update($request->only(['campaign_id', 'product_id', 'reduction', 'active']));

            if ($request->has('filter')) {
                $campaignDetail->filter->update(
                    $this->normalizeFilterInput($request->input('filter', [])),
                );
            }

            if ($request->has('messages')) {
                $campaignDetail->messageCampaigns()->delete();
                $this->syncMessages($campaignDetail, $request->input('messages', []));
            }

            return $campaignDetail;
        });

        $campaignDetail->load(['campaign', 'filter', 'store', 'product', 'messageCampaigns.message']);

        return new CampaignDetailResource($campaignDetail);
    }

    public function destroy(Request $request, CampaignDetail $campaignDetail): JsonResponse
    {
        $this->authorize('delete', $campaignDetail);

        DB::transaction(function () use ($campaignDetail): void {
            $filterId = $campaignDetail->filter_id;
            $campaignDetail->messageCampaigns()->delete();
            $campaignDetail->delete();
            Filter::query()->whereKey($filterId)->delete();
        });

        return response()->json(['message' => 'Campaign configuration deleted.']);
    }

    /**
     * @param  array<string, mixed>  $filterInput
     * @return array<string, mixed>
     */
    private function normalizeFilterInput(array $filterInput): array
    {
        $sexe = (string) ($filterInput['sexe'] ?? 'both');

        return [
            'age_min' => (int) ($filterInput['age_min'] ?? 0),
            'age_max' => (int) ($filterInput['age_max'] ?? 100),
            'sexe' => $this->normalizeSexeForStorage($sexe),
            'points' => (int) ($filterInput['points'] ?? 0),
            'last_visit' => (int) ($filterInput['last_visit'] ?? 0),
            'heure_start' => (string) ($filterInput['heure_start'] ?? '00:00:00'),
            'heure_end' => (string) ($filterInput['heure_end'] ?? '23:59:59'),
        ];
    }

    private function normalizeSexeForStorage(string $sexe): string
    {
        return match ($sexe) {
            '0', 'female' => 'female',
            '1', 'male' => 'male',
            default => 'both',
        };
    }

    /**
     * @param  list<array{message?: string, date_start?: int}>  $messages
     */
    private function syncMessages(CampaignDetail $detail, array $messages): void
    {
        foreach ($messages as $payload) {
            if (empty($payload['message'])) {
                continue;
            }

            $message = Message::query()->create([
                'message' => (string) $payload['message'],
                'date_start' => (int) ($payload['date_start'] ?? 0),
            ]);

            MessageCampaign::query()->create([
                'message_id' => $message->id,
                'campaign_detail_id' => $detail->id,
            ]);
        }
    }
}
