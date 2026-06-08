<?php

namespace App\Jobs;

use App\Models\User;
use App\Support\Logging\IntegrationLogger;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ActivateAccountJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  list<int>  $storeIds
     */
    public function __construct(
        public int $ownerId,
        public array $storeIds,
    ) {}

    public function handle(): void
    {
        User::query()->whereKey($this->ownerId)->update(['is_active' => 1]);

        if ($this->storeIds !== []) {
            User::query()
                ->whereIn('store_id', $this->storeIds)
                ->update(['is_active' => 1]);
        }

        IntegrationLogger::info('account.activated', [
            'owner_id' => $this->ownerId,
            'store_ids' => $this->storeIds,
        ]);
    }
}
