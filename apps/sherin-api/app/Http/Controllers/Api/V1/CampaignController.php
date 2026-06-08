<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CampaignController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Campaign::class);

        $query = Campaign::query()->orderBy('id');

        if ($request->boolean('exclude_credit')) {
            $query->where('type', '!=', 'credit-sup');
        }

        return CampaignResource::collection($query->get());
    }
}
