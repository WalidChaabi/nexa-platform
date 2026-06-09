<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Activities\StoreActivityRequest;
use App\Http\Requests\Activities\UpdateActivityRequest;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ActivityController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Activity::class);

        $activities = Activity::query()
            ->withCount('stores')
            ->orderBy('name')
            ->paginate($request->integer('per_page', 50));

        return ActivityResource::collection($activities);
    }

    public function store(StoreActivityRequest $request): JsonResponse
    {
        $activity = Activity::query()->create([
            'name' => $request->string('name')->toString(),
            'description' => $request->input('description'),
        ]);

        return (new ActivityResource($activity))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Request $request, Activity $activity): ActivityResource
    {
        $this->authorize('view', $activity);

        $activity->loadCount('stores');

        return new ActivityResource($activity);
    }

    public function update(UpdateActivityRequest $request, Activity $activity): ActivityResource
    {
        $activity->update($request->validated());

        return new ActivityResource($activity);
    }

    public function destroy(Request $request, Activity $activity): JsonResponse
    {
        $this->authorize('delete', $activity);

        if ($activity->stores()->exists()) {
            return response()->json([
                'message' => 'Cannot delete an activity that is assigned to stores.',
            ], 422);
        }

        $activity->delete();

        return response()->json(['message' => 'Activity deleted.']);
    }
}
